<template>
    <div class="container-fluid pl-2 pr-2">
        <div class="row g-3">
            <div class="col-lg-4 purchase-products pr-0">
                <div class="wiz-card sell-card-group">
                    <div class="wiz-card-header py-2">
                        <h6 class="wiz-card-title">{{lang.purchase_products}}</h6>
                        <a href="javascript:void(0)" @click="chooseSupplier()" class="btn btn-brand-warning btn-brand btn-sm">
                            {{supplier.company_name}} <i class="fa fa-pencil-alt"></i>
                        </a>
                    </div>
                    <div class="wiz-card-body sell-card-body selected-products sell-cart-scroll">
                        <div class="item mb-3 px-2" v-for="(cart, index) in carts">
                            <div class="row g-2 align-items-center small">
                                <div class="col-3">
                                    <img :src="'../../'+cart.thumbnail" class="img-fluid" v-if="cart.thumbnail != null" height="50px">
                                    <img :src="'../../images/default.png'" class="img-fluid" v-if="cart.thumbnail == null" height="50px">
                                </div>

                                <div class="col-7 description">
                                    <span class="product-details">{{cart.title}}</span>
                                    <br>
                                    <p class="m-0">{{cart.quantity}} x {{cart.purchase_price}} = {{cart.total_price | formatNumber}}/=</p>
                                </div>

                                <div class="col-2 text-center">
                                    <div>
                                        <a href="javascript:void(0)" @click="setPriceAndQty(cart.id)" class="mx-1 text-brand-primary"><i class="bi bi-eye"></i> </a>
                                        <a href="javascript:void(0);" @click="deleteProductFormCart(index)" class="mx-1 text-brand-danger"><i class="bi bi-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wiz-card-footer border-top pt-1">
                        <div>
                            <table class="table text-right wiz-table overflow-hidden">
                                <tbody>
                                <tr>
                                    <td>{{lang.total_amount}}</td>
                                    <td style="width: 125px">
                                        <strong>
                                            <input type="number" v-model.number="summary.total_amount = totalCartsValue" class="form-control form-control-sm payment-box" readonly>
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{lang.paid_amount}} </td>
                                    <td>
                                        <input type="number" v-model.number="summary.paid_amount" class="form-control form-control-sm payment-box">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{lang.due_amount}} </td>
                                    <td>
                                        <input type="number" v-model.number="summary.due_amount = currentDue" class="form-control form-control-sm payment-box" readonly>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <div class="text-end">
                                <a href="javascript:void(0)" @click="updatePurchase()" class="btn btn-brand btn-brand-primary">Save & Update</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 all-products" >
                <div class="sell-card-group">
                    <div class="sell-card-header">
                        <div class="wiz-box p-2">
                            <div class="input-with-icon">
                                <span class="input-icon"><i class="bi bi-search"></i></span>
                                <input type="text" v-model="filter.search" v-on:keyup.enter="onEnterClick" class="form-control" :placeholder="lang.product_search_kye">
                            </div>
                        </div>
                        <div class="perfect-ps category-scrollbox my-2">
                            <div class="filter-category d-flex align-items-center m-n2">
                                <a href="javascript:void(0)" class="filter-category-btn" :class="{active : filter.category_id == ''}" v-on:click="filter.category_id = ''">{{lang.all}}</a>
                                <a href="javascript:void(0)" class="filter-category-btn" v-for="category in categories" :class="{active : filter.category_id == category.id}" @click="productFilterByCategory(category.id)">{{category.title}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="sell-card-body sell-card-product-scroll">
                        <div class="p-2">
                            <div class="row g-3 grid-xl-5 justify-content-center all-products">
                                <div class="col-6 col-md-4 col-lg-3" v-for="(product, index) in filteredProduct">
                                    <div class="single-product item" :class="{selected : isAlreadyInCart(product.id)}" @click="setPriceAndQty(product.id)">
                                        <!--                                    <div class="img">-->
                                        <!--                                        <img :src="'../../'+product.thumbnail" class="img-fluid" v-if="product.thumbnail != null">-->
                                        <!--                                        <img :src="'../../images/default.png'" class="img-fluid" v-if="product.thumbnail == null">-->
                                        <!--                                    </div>-->
                                        <!--                                    <div class="description">-->
                                        <!--                                        <p class="product-title"><strong>{{product.title}}</strong></p>-->
                                        <!--                                        <div class="d-flex sku-price">-->
                                        <!--                                            <div class="col-12 pl-0 pt-0">-->
                                        <!--                                                <span>Sku : {{product.sku}}</span>-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <!--                                    </div>-->
                                        <!--                                    <div class="price"> {{product.sell_price}}</div>-->

                                        <div class="ratio ratio-16x9">
                                            <div class="single-product-header">
                                                <img :src="'../'+product.thumbnail" class="img-fluid" v-if="product.thumbnail != null">
                                                <img :src="'../images/default.png'" class="img-fluid" v-if="product.thumbnail == null">
                                            </div>
                                        </div>

                                        <div class="single-product-body">
                                            <div class="d-flex justify-content-between gap-2">
                                                <div>
                                                    <h6 class="single-product-title">
                                                        {{product.title}}
                                                    </h6>
                                                </div>
                                                <div class="single-product-price">{{appConfig('app_currency')}} {{product.purchase_price}}</div>
                                            </div>
                                            <div class="single-sku-price">
                                                <small class="extra-small">Sku: {{product.sku}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="drawer shadow right responsive-drawer" :class="{show: setPrice}" v-if="setPrice">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="card-title">Set Price & Quantity</h6>
                    <button class="close" @click="closeDrawer()"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class=card-body>

                    <div class="selected-products">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="custom-label">Purchase Price <span class="text-danger">*</span></label>
                                    <input type="number" min="0" v-model.number="product.purchase_price" placeholder="Purchase Price" class="form-control" tabindex="-1">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="custom-label">Quantity <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <input type="number" v-model.number="product.quantity" min="1" placeholder="Quantity" class="form-control" autofocus>
                                    <span class="input-group-text" id=''>{{ product.unit ? product.unit.title : '--'}}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group d-flex gap-2 justify-content-between h5">
                                    <label>Total Price: </label>
                                    <div>
                                        <input type="hidden" v-model.number="product.total_price = product.quantity * product.purchase_price" step=".01" placeholder="Sell Price" class="form-control" readonly>
                                        {{product.total_price | formatNumber}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group text-end">
                                    <button type="submit" class="btn btn-brand btn-brand-primary" @click="addToCart()">Save</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer" :class="{show: updatePriceAndQty}" v-if="updatePriceAndQty">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="card-title">Update Price & Quantity</h6>
                    <button class="close" @click="updatePriceAndQty = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class=card-body>

                    <div class="selected-products">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="custom-label">Purchase Price <span class="text-danger">*</span></label>
                                    <input type="number" v-model.number="product.purchase_price" placeholder="Purchase Price" class="form-control" tabindex="-1">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="custom-label">Quantity <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <input type="number" v-model.number="product.quantity" min="1" placeholder="Quantity" class="form-control" autofocus>
                                    <span class="input-group-text" id="basic-addon2">{{ product.unit ? product.unit.title : '--'}}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group d-flex gap-2 justify-content-between h5">
                                    <label>Total Price: </label>
                                    <div>
                                        <input type="hidden" v-model.number="product.total_price = product.quantity * product.purchase_price" step=".01" placeholder="Sell Price" class="form-control" readonly>
                                        {{product.total_price | formatNumber}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group text-end">
                                    <button type="submit" class="btn btn-brand btn-brand-primary" @click="addToCart()">Save</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer" :class="{show: setSupplier}" v-if="setSupplier" style="width: 400px">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="card-title">Select Supplier</h6>
                    <button class="close" @click="closeDrawer()"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body p-0">
                    <div class="form-group p-2">
                        <input type="text" v-model="filter.supplier_search" autofocus class="form-control" placeholder="Find Supplier by Name,Phone,Email">
                    </div>
                    <div class="list-group">
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action rounded-0"  v-for="supplier in filteredSuppler" @click="selectSuppler(supplier.id)" >
                            {{supplier.company_name}} , {{supplier.phone}}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer drawer-lg" :class="{show: printInvoice}" v-if="printInvoice">

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{lang.purchase_details}}</h6>
                    <button class="close" @click="printInvoice = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="sell-invoice mb-3">
                        <div class="mb-2 text-end" role="group" aria-label="Basic example">
                            <a v-bind:href="'../../export/purchase/print-invoice/id='+purchase.id+'/type={print}'" @click="printInvoice = false" target="_blank" class="btn btn-brand btn-warning btn-sm">
                                <i class="fa fa-print"></i> {{lang.print_invoice}}
                            </a>
                        </div>

                        <div class="text-center">
                            <h3 class="company-name mb-1">{{appConfig('app_name')}}</h3>
                            <p class="address mb-1">{{lang.address}}: {{appConfig('address')}}</p>
                            <p class="vat mb-1">{{lang.vat_reg_number}} : {{appConfig('vat_reg_no')}}</p>
                            <p class="outlet mb-0">{{lang.outlet}}: {{my_branch.title}} </p>
                        </div>
                    </div>

                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex gap-4 invoice-summary small justify-content-around">
                            <div>
                                <div class="mb-1">
                                    <span>{{lang.suppler_name}}:
                                        {{purchase.supplier.company_name}}</span>
                                </div>
                                <div>
                                    <span>{{lang.phone_number}}: {{purchase.supplier.phone}}</span>
                                </div>
                            </div>

                            <div>
                                <div class="mb-1">
                                    <span>{{lang.invoice_id}}: {{purchase.invoice_id}}</span>
                                </div>
                                <div>
                                    <span>{{lang.date}}: {{purchase.custom_purchase_date}}</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-sm wiz-table mw-col-width-skip-first">
                            <thead>
                            <tr class="bg-secondary text-white">
                                <th>{{lang.sl}}</th>
                                <th>{{lang.product}}</th>
                                <th>{{lang.purchase_price}}</th>
                                <th>{{lang.quantity}}</th>
                                <th>{{lang.total_price}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(purchase_product, index) in purchase.purchase_products">
                                <td width="3%">{{index + 1}}</td>
                                <td>
                                    {{purchase_product.product.title}}
                                </td>
                                <td>{{appConfig('app_currency')}}{{purchase_product.purchase_price | formatNumber}} </td>
                                <td>{{purchase_product.quantity}} {{purchase_product.product.unit ? purchase_product.product.unit.title : ''}}</td>
                                <td>{{appConfig('app_currency')}}{{purchase_product.total_price | formatNumber}} </td>
                            </tr>

                            <tr>
                                <td colspan="4" class="text-right pr-5">
                                    {{lang.total_amount}}:
                                </td>
                                <td>
                                    {{appConfig('app_currency')}}{{purchase.total_amount | formatNumber}}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="4" class="text-right pr-5">
                                    {{lang.paid_amount}}:
                                </td>
                                <td>
                                    {{appConfig('app_currency')}}{{purchase.paid_amount | formatNumber}}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="4" class="text-right pr-5">
                                    {{lang.due_amount}}:
                                </td>
                                <td>
                                    {{appConfig('app_currency')}}{{purchase.due_amount | formatNumber}}
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "EditPurchase",
        mounted() {
            const ps = new PerfectScrollbar('.perfect-ps');
            const psForProduct = new PerfectScrollbar('.sell-card-product-scroll');
            const psForCart = new PerfectScrollbar('.sell-cart-scroll');
        },
        props : {
            purchase : Object,
        },
        data() {
            return {
                configs: [],
                my_branch: [],
                lang: [],
                products: [],
                product: {},
                suppliers: [],
                supplier: Object,
                categories: [],
                brands: [],
                carts: [],
                summary:{
                    'total_amount': 0,
                    'paid_amount': this.purchase.paid_amount,
                    'due_amount': this.purchase.due_amount,
                },
                filter: {
                    search: '',
                    supplier_search: '',
                    category_id: '',
                },
                grand_total_price:0,
                setPrice: false,
                setSupplier: false,
                updatePriceAndQty: false,
                printInvoice:false
            }
        },

        methods: {
            productFilterByCategory:function(category_id){
                this.filter.category_id = category_id;
            },

            onEnterClick: function() {
                this.products.forEach((product) => {
                    if (product.sku.toLowerCase() == this.filter.search.toLowerCase()){
                        this.setPrice = true;
                        this.product = product;
                        this.filter.search = '';
                    }
                });
            },

            setPriceAndQty: function (product_id) {
                if (this.isAlreadyInCart(product_id)) {
                    this.products.forEach((product) => {
                        if (product.id == product_id) {
                            this.product = product;
                        }
                    });

                    this.carts.forEach((element) => {
                        if (element.id == product_id) {
                            this.product.purchase_price = element.purchase_price;
                            this.product.quantity = element.quantity;
                            this.product.total_price = element.total_price;
                        }
                    });
                    this.updatePriceAndQty = true;
                }else {
                    this.setPrice = true;
                    this.products.forEach((product) => {
                        if (product.id == product_id) {
                            this.product = product;
                        }
                    });
                }
            },

            addToCart: function(){
                if (this.isAlreadyInCart(this.product.id)) {
                    this.setPrice = false;
                    this.carts.forEach((element) => {
                        if (element.id == this.product.id) {
                            element.purchase_price = this.product.purchase_price;
                            element.quantity = this.product.quantity;
                            element.total_price = this.product.total_price;
                        }
                    });
                    this.updatePriceAndQty= false;
                }else {
                    this.carts.push(this.product);
                    this.setPrice = false;
                }
            },

            isAlreadyInCart: function (cart_product_id) {
                let result = false;
                this.carts.forEach((element) => {
                    if (element.id == cart_product_id) {
                        result = true
                    }
                });
                return result;
            },

            deleteProductFormCart: function (index) {
                this.carts.splice(index, 1)
            },

            chooseSupplier:function (){
                if (this.setPrice == true) {
                    this.setPrice = false;
                }
                this.setSupplier = true;
            },

            selectSuppler:function(supplier_id){
                this.setSupplier = false;
                this.suppliers.forEach((supplier) => {
                    if (supplier.id == supplier_id) {
                        this.supplier = supplier;
                    }
                });
            },

            updatePurchase:function(){
                if (this.carts.length != 0) {
                    axios.patch('../../purchase/' + this.purchase.id, {carts: JSON.parse(JSON.stringify(this.carts)), supplier: JSON.parse(JSON.stringify(this.supplier)), summary: JSON.parse(JSON.stringify(this.summary))}).then((response) => {
                        this.printInvoice = true;
                        this.purchase = response.data;
                        this.purchase.custom_purchase_date = response.data.custom_purchase_date;
                    }).catch((error) =>{
                        console.error(error);
                    });
                }else{
                    alert('Empty carts !');
                }
            },

            closeDrawer: function () {
                if (this.setPrice == true) {
                    this.setPrice = false;
                }
                if (this.setSupplier == true) {
                    this.setSupplier = false;
                }
            },

            clearAll:function(){
                this.supplier = Object;
                this.carts = [];
                this.summary.total_amount = 0;
                this.summary.paid_amount = 0;
                this.summary.due_amount = 0;
                this.summary.payment = 0;
                this.filter.search = '';
                this.filter.supplier_search = '';
            },

            appConfig: function (option_key) {
                let result;
                this.configs.forEach((element) => {
                    if (element.option_key == option_key) {
                        result = element.option_value;
                        return false;
                    }
                });

                return result;
            }
        },

        computed: {
            totalCartsValue(){
                let total = 0;
                this.carts.forEach((cart) => {
                    total += cart.total_price;
                });

                if (total == 0) {
                    total = this.purchase.total_amount;
                }

                return parseFloat((total).toFixed(2));
            },

            currentDue(){
                if(this.summary.paid_amount > this.totalCartsValue){
                    this.summary.paid_amount = 0;
                }

                let due = this.totalCartsValue - this.summary.paid_amount;
                return parseFloat((due).toFixed(2));
            },

            filteredProduct: function () {
                if (this.filter.category_id != ''){
                    return this.products.filter((product) => {
                        return product.category_id == (this.filter.category_id)
                            && (product.sku.toLowerCase().match(this.filter.search.toLowerCase()) || product.title.toLowerCase().match(this.filter.search.toLowerCase()));

                    });
                }

                if (this.filter.search != '') {
                    return this.products.filter((product) => {
                        return product.title.toLowerCase().match(this.filter.search.toLowerCase())
                            || product.sku.toLowerCase().match(this.filter.search.toLowerCase());

                    });
                }

                return this.products;
            },

            filteredSuppler: function () {
                if (this.filter.supplier != '') {
                    return this.suppliers.filter((supplier) => {
                        return supplier.company_name.toLowerCase().match(this.filter.supplier_search.toLowerCase())
                            || supplier.phone.toLowerCase().match(this.filter.supplier_search.toLowerCase());
                    });
                }
                return this.suppliers;
            }
        },

        beforeMount() {
             axios.get('../../vue/api/products').then((response) => {
                this.products = response.data;
            });

             axios.get('../../vue/api/get-local-lang').then((response) => {
                this.lang = response.data;
            });

             axios.get('../../vue/api/get-app-configs').then((response) => {
                this.configs = response.data;
            });

             axios.get('../../vue/api/categories').then((response) => {
                this.categories = response.data;
            });

             axios.get('../../vue/api/purchase-details/' + this.purchase.id).then((response) => {
                this.supplier = response.data.supplier;
                response.data.purchase_products.forEach((purchase_product) => {
                    purchase_product.title = purchase_product.product.title;
                    purchase_product.thumbnail = purchase_product.product.thumbnail;
                    purchase_product.id = purchase_product.product_id;
                    this.carts.push(purchase_product);
                });
            });

             axios.get('../../vue/api/suppliers').then((response) => {
                this.suppliers = response.data;
            });
             axios.get('../../vue/api/my-branch').then((response) => {
                this.my_branch = response.data;
            });
        }
    }
</script>
