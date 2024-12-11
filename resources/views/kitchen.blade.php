<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kitchen Orders | DREBBA POS</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="{{ asset('admin/plugin/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="py-2 d-flex justify-content-evenly" style="background:#0C213A">
        @if (Auth::user()->can('manage_sell'))
        <a class="text-white text-decoration-none" href="{{route('sell.index')}}">
            <h4>⬅ All Sell</h4>
        </a>
        @endif
      <h4 class="text-center text-white">{{ Auth::user()->business->name }} (Kitchen Orders)</h4>
    </div>

    <div class="container-fluid mt-4">
        <!-- Orders List -->
        <div class="row" id="ordersList">
            <p class="text-center">Loading orders...</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('admin/plugin/bootstrap5/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        const ordersList = document.getElementById('ordersList');
        let orders = [];  // Store orders data

        // Function to create an order card
        function createOrderCard(order) {
            const col = document.createElement('div');
            col.className = 'col-md-3';

            const productsList = order.products.map(product => `
                <li>${product.qty} x ${product.name}</li>
            `).join('');

            const card = `
                <div class="card mb-4 rounded shadow">
                    <div class="card-body">
                        <h6 class="card-text">Order Mode: ${order.orderMode} (${order.invoice_id})</h6>
                        <h6 class="card-text">Table: ${order.table}</h6>
                        <ul>${productsList}</ul>
                        <button class='text-center btn btn-primary' id="elapsed-time-${order.id}">${getElapsedTime(order.updated_at)}</button>
                    </div>
                </div>
            `;

            col.innerHTML = card;
            return col;
        }

        // Function to render the orders
        function renderOrders() {
            ordersList.innerHTML = ''; // Clear existing content

            if (orders.length === 0) {
                ordersList.innerHTML = '<p class="text-center">No orders available.</p>';
                return;
            }

            orders.forEach(order => {
                const card = createOrderCard(order);
                ordersList.appendChild(card);
            });
        }

        // Fetch orders from API with headers
        async function fetchOrders() {
            try {
                const response = await fetch('/kitchen?api=true', {
                    method: 'GET',
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch orders');
                }

                const data = await response.json();
                orders = data;  // Update orders array
                renderOrders();  // Render orders after fetching
            } catch (error) {
                console.error('Error fetching orders:', error);
                ordersList.innerHTML = '<p class="text-center text-danger">Failed to load orders. Please try again later.</p>';
            }
        }

        // Get elapsed time based on table's updated_at timestamp
        function getElapsedTime(updated_at) {
            if (!updated_at) return '0:00';  // In case updated_at is missing

            const now = new Date().getTime();
            const startTime = new Date(updated_at).getTime();  // Convert updated_at to timestamp
            const diff = now - startTime;  // Time difference in milliseconds

            const seconds = Math.floor((diff / 1000) % 60);
            const minutes = Math.floor((diff / (1000 * 60)) % 60);
            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);

            return `${hours}:${minutes}:${seconds}`;
        }

        // Update the elapsed time for each order every second
        function updateElapsedTimes() {
            orders.forEach(order => {
                const elapsedTimeElement = document.getElementById(`elapsed-time-${order.id}`);
                if (elapsedTimeElement) {
                    elapsedTimeElement.innerText = getElapsedTime(order.updated_at);
                }
            });
        }

        // Fetch orders initially and every 20 seconds
        fetchOrders();
        setInterval(fetchOrders, 20000);  // Refresh orders every 20 seconds
        setInterval(updateElapsedTimes, 1000);  // Update elapsed time every second
    </script>
</body>
</html>
