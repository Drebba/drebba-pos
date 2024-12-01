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
    <div class="py-2" style="background:#0C213A">
        <h3 class="text-center text-white">{{ Auth::user()->business->name }} (Kitchen Orders)</h3>
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
                    </div>
                </div>
            `;

            col.innerHTML = card;
            return col;
        }

        // Function to render the orders
        function renderOrders(orders) {
            console.log(orders);
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
                renderOrders(data);
            } catch (error) {
                console.error('Error fetching orders:', error);
                ordersList.innerHTML = '<p class="text-center text-danger">Failed to load orders. Please try again later.</p>';
            }
        }

        // Fetch orders initially and every 20 seconds
        fetchOrders();
        setInterval(fetchOrders, 20000);
    </script>
</body>
</html>
