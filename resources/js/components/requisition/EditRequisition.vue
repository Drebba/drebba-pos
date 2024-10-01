<template>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row sell-pos">
            <div class="col-md-4">
                <div class="sell-card-group">


                    <div class="sell-card-body">
                        <div class="wiz-box d-flex flex-column h-100">
                            <div class="cart-products sell-card position-relative sell-cart-scroll">
                                <div class="border-bottom py-2 px-2" v-for="(cart, key) in carts">
                                    <div class="row g-2 sell-item align-items-center">
                                        <div class="col-md-6">
                                            <span class="product-title small">{{cart.title}} </span>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" v-model.number="cart.quantity" min="1" class="form-control form-control-sm">
                                                        <span class="input-group-text extra-small" v-if="cart.product">{{ cart.product.unit ? cart.product.unit.title.substring(0, 7) : '--' }}</span>
                                                        <span class="input-group-text extra-small" v-if="!cart.product">{{ cart.unit ? cart.unit.title.substring(0, 7) : '--' }}</span>
                                                    </div>
                                                </div>

                                                <div class="ms-auto ps-3">
                                                    <a href="javascript:void(0)" class="text-danger remove" @click="deleteProductFormCart(key)">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wiz-card-footer px-2 pb-0">
                                <div class="d-flex gap-2 justify-content-end align-items-center">
                                    <div>
                                        <a href="javascript:void(0)" class="btn btn-brand btn-brand-primary btn-sm" @click="storeRequisition()">{{lang.send_requst}}</a>
                                    </div>

                                    <div>
                                        <a href="javascript:void(0)" class="btn btn-brand btn-brand-danger btn-sm" @click="clearAll()">{{lang.clear}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="sell-card-group">
                    <div class="sell-card-header pb-3">
                        <div class="px-md-2">
                            <div class="wiz-box">
                                <div class="input-with-icon">
                                    <span class="input-icon"><i class="bi bi-search"></i></span>
                                    <input type="text" v-model="filter.search" class="form-control" placeholder="Find Product by Name,Sku">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sell-card-body sell-card-product-scroll px-md-2">
                        <div class="row g-3 grid-xl-5 justify-content-center all-products">
                            <div class="col-6 col-sm-4 col-lg-3" v-for="(product, index) in filteredProduct">
                                <div class="single-product" :class="{selected : isAlreadyInCart(product.id)}" @click="addToCart(product.id)">
                                    <div class="ratio ratio-16x9">
                                        <div class="single-product-header">
                                            <img :src="'../'+product.thumbnail" class="img-fluid" v-if="product.thumbnail != null">
                                            <img :src="'../images/default.png'" class="img-fluid" v-if="product.thumbnail == null">
                                        </div>
                                    </div>
                                    <div class="single-product-body">
                                        <h6 class="single-product-title">
                                            <span>{{product.title}}</span>
                                        </h6>
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



        <div class="drawer shadow right responsive-drawer-lg drawer-lg" :class="{show: printInvoice}" v-if="printInvoice">

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="card-title">Invoice</h6>
                    <button class="close" @click="printInvoice = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">

                    <div class="mb-4 border-bottom pb-3">
                        <div class="mb-2 text-end" role="group" aria-label="Basic example">
                            <a v-bind:href="'/export/requisition/print-invoice/id='+requisition.id+'/type={print}'" target="_blank" class="btn btn-brand-warning btn-brand btn-sm">
                                <i class="fa fa-print"></i> {{lang.print_invoice}}
                            </a>
                        </div>

                        <div class="text-center">
                            <h2 class="company-name mb-1">{{appConfig('app_name')}}</h2>
                            <p class="address mb-1">{{lang.address}}: {{appConfig('address')}}</p>
                            <p class="vat mb-0">{{lang.var_reg_number}} : {{appConfig('vat_reg_no')}}</p>
                        </div>
                    </div>


                    <div class="row mb-4">

                        <div class="col-md-12">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <table class="table table-bordered wiz-table table-sm">
                                        <thead>
                                        <tr class="bg-secondary text-white">
                                            <th colspan="2">{{lang.requisition_form}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{lang.branch}}</td>
                                            <td>{{requisition.requisition_from.title}}</td>
                                        </tr>

                                        <tr>
                                            <td>{{lang.phone_number}}</td>
                                            <td>{{requisition.requisition_from.phone}}</td>
                                        </tr>

                                        <tr>
                                            <td>{{lang.address}}</td>
                                            <td>{{requisition.requisition_from.address}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-4"></div>

                                <div class="col-md-4">
                                    <table class="table table-bordered wiz-table table-sm">
                                        <thead>
                                        <tr class="bg-secondary text-white">
                                            <th colspan="2">{{lang.requisition_to}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{lang.branch}}</td>
                                            <td>{{requisition.requisition_to.title}}</td>
                                        </tr>

                                        <tr>
                                            <td>{{lang.phone_number}}</td>
                                            <td>{{requisition.requisition_to.phone}}</td>
                                        </tr>

                                        <tr>
                                            <td>{{lang.address}}</td>
                                            <td>{{requisition.requisition_to.address}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>




                        <div class="table-responsive">
                            <table class="table table-bordered text-center table-sm wiz-table mw-col-width-skip-first">
                                <thead>
                                <tr class="bg-secondary text-white">
                                    <th>{{lang.sl}}</th>
                                    <th>{{lang.product}}</th>
                                    <th>{{lang.quantity}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-for="(requisition_product, index) in requisition.requisition_products">
                                    <td width="3%">{{index + 1}}</td>
                                    <td> {{requisition_product.product.title}} </td>
                                    <td>{{requisition_product.quantity}} {{requisition_product.product.unit ? requisition_product.product.unit.title : ''}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</template>

<script>
    export default {
        mounted() {
            const psForProduct = new PerfectScrollbar('.sell-card-product-scroll');
            const psForCart = new PerfectScrollbar('.sell-cart-scroll');
        },
        name: "EditRequisition",
        props : {
            requisition : Object,
        },
        data() {
            return {
                lang: [],
                products: [],
                product: {},
                carts: [],
                branch: {},
                filter: {
                    search: '',
                },
                printInvoice:false
            }
        },

        methods: {
            addToCart: function(product_id){
                if (this.isAlreadyInCart(product_id)) {
                    this.carts.forEach((cart) => {
                        if (cart.id == product_id) {
                            cart.quantity = cart.quantity + 1;
                        }
                    });
                }else {
                    this.products.forEach((product) => {
                        if (product.id == product_id) {
                            this.product = product;
                            this.product.quantity = 1;
                            this.carts.unshift(this.product);
                        }
                    });
                }
            },

            isAlreadyInCart: function (product_id) {
                let result = false;
                this.carts.forEach((element) => {
                    if (element.id == product_id) {
                        result = true
                    }
                });
                return result;
            },

            deleteProductFormCart: function (key) {
                this.carts.splice(key, 1)
            },

            storeRequisition:function(){
                if (this.requisitionStoreValidation()){
                    if (this.carts.length != 0){
                        axios.post('/vue/api/update-requisition', {carts: JSON.parse(JSON.stringify(this.carts)), branch: JSON.parse(JSON.stringify(this.branch)), requisition_id: this.requisition.id}).then((response) => {
                            this.requisition = response.data;
                            console.log(this.requisition)
                            this.printInvoice = true;
                        }).catch((error) =>{
                            console.error(error);
                        });
                    }else {
                        alert('!Empty Cart')
                    }
                }
            },

            clearAll:function(){
                this.branch = {};
                this.carts = [];
                this.filter.search = '';
            },

            requisitionStoreValidation:function () {
                if (this.branch.id != null){
                    return true;
                }else{
                    alert('Please select a Branch');
                    return false;
                }
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
            filteredProduct: function () {
                if (this.filter.product != '') {
                    return this.products.filter((product) => {
                        return product.title.toLowerCase().match(this.filter.search.toLowerCase()) || product.sku.toLowerCase().match(this.filter.search.toLowerCase());
                    });
                }
                return this.products;
            }
        },

        beforeMount() {
            axios.get('../../vue/api/products').then((response) => {
                this.products = response.data;
            });

            axios.get('../../vue/api/requisition-details/' + this.requisition.id).then((response) => {
                this.branch = response.data.requisition_to;
                response.data.requisition_products.forEach((requisition_product) => {
                    requisition_product.title = requisition_product.product.title;
                    requisition_product.id = requisition_product.product.id;
                    this.carts.push(requisition_product);
                });
            });

            axios.get('../../vue/api/get-local-lang').then((response) => {
                this.lang = response.data;
            });



            axios.get('../../vue/api/get-app-configs').then((response) => {
                this.configs = response.data;
            });
        }
    }
</script>
