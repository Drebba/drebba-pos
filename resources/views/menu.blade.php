<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MENU | DREBBA POS</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="{{asset('admin/plugin/bootstrap5/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="py-2" style="background:#0C213A">
        <h3 class="text-center text-white">{{$business->name}} (Menu List)</h3>
    </div>

    <div class="container-fluid mt-4">
        <!-- Search Bar -->
        <div class="row mb-3">
            <div class="col-md-12">
                <input type="text" id="menuSearch" class="form-control" placeholder="Search menu items...">
            </div>
        </div>

        <div class="row" id="menuList">
            <!-- Cards will be dynamically inserted here -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{asset('admin/plugin/bootstrap5/js/bootstrap.bundle.min.js')}}"></script>
    <script>
        // Sample menu data
        const menus = @json($menus);

        const menuList = document.getElementById('menuList');
        const searchInput = document.getElementById('menuSearch');

        // Function to create a card
        function createCard(menu) {
            const col = document.createElement('div');
            col.className = 'col-md-2 menu-card';

            const card = `
                <div class="card mb-4 rounded">
                    <img src="${menu.thumbnail ? menu.thumbnail : '{{ asset("type/default-image.webp") }}'}" class="card-img-top rounded" alt="${menu.title}" loading="lazy">
                    <div class="card-body">
                        <h5 class="card-title">${menu.title}</h5>
                        <div>
                            <span>{{get_option('app_currency')}} ${menu.sell_price}</span>
                        </div>
                    </div>
                </div>
            `;

            col.innerHTML = card;
            return col;
        }

        // Function to render the menu list
        function renderMenuList(filter = '') {
            menuList.innerHTML = ''; // Clear existing content

            menus
                .filter(menu => menu.title.toLowerCase().includes(filter.toLowerCase()))
                .forEach(menu => {
                    const card = createCard(menu);
                    menuList.appendChild(card);
                });
        }

        // Initial render of all menus
        renderMenuList();

        // Add event listener for search
        searchInput.addEventListener('input', () => {
            const query = searchInput.value;
            renderMenuList(query);
        });
    </script>
</body>
</html>
