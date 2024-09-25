<template>
    <!-- Begin Page Content -->
    <div class="container-fluid pl-2 pr-2">
        <div class="row g-3 sell-pos">
            <div class="col-md-7">

                <div class="sell-card-group">
                    <div class="sell-card-header  pb-2 mb-2">
                        <div class="wiz-box p-2">
                            <div class="d-flex gap-2">
                                <div class="flex-grow-1 select-customer">
                                    <v-select  :options="customers" v-model="customer" label="name" placeholder="Search Customer"></v-select>
                                </div>
                                <div>
                                    <div class="form-group">
                                        <button class="btn btn-warning btn-sm text-white btn-block" @click="createCustomer = true"> <i class="fa fa-plus"></i> </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sell-card-body">
                        <div class="wiz-box d-flex flex-column h-100">
                            <div class="cart-products sell-card position-relative sell-cart-scroll">
                                <h1 v-if="carts.length == 0" style="text-align: center; color:#d1d1d1;margin-top: 100px">{{lang.empty_carts}}</h1>
                                <table class="table table-bordered table-sm wiz-table text-12" v-if="carts.length > 0">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span>{{lang.product_title}}</span>
                                        </th>
                                        <th class="text-center">
                                            <span>{{lang.sell_price}}</span>
                                        </th>
                                        <th class="text-center">
                                            <span>{{lang.tax}}</span>
                                        </th>

                                        <th class="text-center">
                                            {{lang.qty}}
                                        </th>
                                        <th class="text-center">
                                            {{lang.total}}
                                        </th>
                                        <th class="text-center">
                                            Action
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(cart, key) in carts">
                                        <td>
                                            <span class="cart-product-title" v-if="cart.title.length < 35">{{cart.title}} </span>
                                            <span class="cart-product-title" v-else>{{ cart.title.substring(0,35)+".." }} </span>
                                        </td>

                                        <td class="text-center w-100px">
                                            <input type="number" v-model.number="cart.sell_price" step=".1" min=".1" value="10" class="form-control form-control-sm text-center" v-if="cart.price_type == 1" readonly>
                                            <input type="number" v-model.number="cart.sell_price" step=".1" min=".1" value="10" class="form-control form-control-sm text-center" v-if="cart.price_type == 2">
                                        </td>
                                        <td class="text-center w-100px">
                                            <input type="hidden" v-model="cart.tax_percentage = cart.tax.value">
                                            <input type="number" v-model.number="cart.tax_amount = (cart.sell_price * cart.tax.value/ 100).toFixed(2)" step=".1" min=".1" value="10" class="form-control form-control-sm text-center" readonly>
                                        </td>

                                        <td class="text-center w-125px">

                                            <div class="input-group input-group-sm">
                                                <input type="number" v-model.number="cart.quantity" min="1" class="form-control form-control-sm text-center">
                                                <span class="input-group-text bg-transparent" v-if="cart.unit">{{ cart.unit ? cart.unit.title.substring(0,3) : '--'}}</span>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <input type="hidden" v-model.number="cart.total_price">
                                            <small class="price" style="display: none">
                                                {{appConfig('app_currency')}}{{cart.total_price = cart.quantity * (parseFloat(cart.sell_price) + parseFloat(cart.tax_amount))}}
                                            </small>

                                            <small class="price">
                                                {{appConfig('app_currency')}}{{cart.total_price | formatNumber}}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="text-brand-danger remove" @click="deleteProductFormCart(key)">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="wiz-card-footer">
                                <div class="d-flex justify-content-end">
                                    <table class="table table-sm table-bordered wiz-table w-auto">
                                        <tbody>
                                        <tr>
                                            <td> {{lang.total}}</td>
                                            <td width="50%">
                                                <input type="number" v-model.number="summary.sub_total = subTotalotalCartsValue" class="form-control form-control-sm" readonly>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>{{lang.discount}} </td>
                                            <td>
                                                <input type="number" v-model="summary.discount" class="form-control form-control-sm">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>{{lang.grand_total}}</td>
                                            <td width="50%">
                                                <input type="number" v-model.number="summary.grand_total = grandTotalotalCartsValue" class="form-control form-control-sm" readonly>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex gap-1 justify-content-end pe-2">
                                    <div><a href="javascript:void(0)" class="btn btn-brand btn-brand-primary btn-sm" @click="createPayment()">{{lang.payment}}</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="sell-card-group">
                    <div class="sell-card-header">
                        <div class="wiz-box p-2">
                            <div class="input-with-icon">
                                <span class="input-icon"><i class="bi bi-search"></i></span>
                                <input type="text" v-model="filter.search" v-on:keyup.enter="onEnterClick" class="form-control form-control-sm" :placeholder="lang.product_search_kye">
                            </div>
                        </div>
                        <div class="perfect-ps category-scrollbox my-2">
                            <div class="filter-category d-flex align-items-center m-n2">
                                <a href="javascript:void(0)" class="filter-category-btn" :class="{active : filter.category_id == ''}" v-on:click="filter.category_id = ''">{{lang.all}}</a>
                                <a href="javascript:void(0)" class="filter-category-btn" :class="{active : filter.category_id == category.id}" v-for="category in categories" @click="productFilterByCategory(category.id)">{{category.title}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="sell-card-body sell-card-product-scroll">
                        <div class="row g-3 justify-content-center all-products">
                            <div class="col-md-4 col-6" v-for="(product, index) in filteredProduct">
                                <div class="single-product" :class="{selected : isAlreadyInCart(product.id)}" @click="addToCart(product.id)">
                                    <div class="ratio ratio-16x9">
                                        <div class="single-product-header">
                                            <img :src="'../../'+product.thumbnail" class="img-fluid" v-if="product.thumbnail != null">
                                            <img :src="'../../images/default.png'" class="img-fluid" v-if="product.thumbnail == null">
                                        </div>
                                    </div>

                                    <div class="single-product-body">
                                        <div class="d-flex justify-content-between gap-2">
                                            <div>
                                                <h6 class="single-product-title">
                                                    <strong v-if="product.title.length < 15">{{ product.title}}</strong>
                                                    <strong v-else>{{ product.title.substring(0,15)+".." }} </strong>
                                                </h6>
                                            </div>
                                            <div class="single-product-price">{{appConfig('app_currency')}}{{product.sell_price}}</div>
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

        <div class="drawer shadow right responsive-drawer" :class="{show: createCustomer}" v-if="createCustomer">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="card-title">{{lang.create_customer}}</h6>
                    <button class="close" @click="closeDrawer()"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="custom-label">Name <span class="text-danger">*</span></label>
                                <input type="text" v-model="new_customer.name" placeholder="Full Name" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="custom-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" v-model="new_customer.phone" placeholder="Phone Number" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="custom-label">Email</label>
                                <input type="email" v-model="new_customer.email"  placeholder="Email Address" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="custom-label">Address</label>
                                <input type="text" v-model.number="new_customer.address" placeholder="Address" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group d-flex justify-content-end">
                                <button type="submit" class="btn btn-brand-primary btn-brand" @click="storeCustomer()">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer drawer-lg" :class="{show: createPaymentDrawer}" v-if="createPaymentDrawer">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="card-title">{{lang.sell_details}}</h6>
                    <button class="close" @click="closeCreatePaymentDrawer()"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body p-0">
                    <div class="text-center bg-soft-primary p-3">
                        <h3 class="company-name mb-1">{{appConfig('app_name')}}</h3>
                        <p class="address mb-1">{{lang.address}}: {{appConfig('address')}}</p>
                        <p class="vat mb-1">{{lang.vat_reg_number}} : {{appConfig('vat_reg_no')}}</p>
                        <p class="outlet mb-0">{{lang.outlet}}: {{my_branch.title}}</p>
                    </div>

                    <div class="p-3">
                        <div class="row g-3">
                            <div class="col-md-8 sell-invoice">


                                <div v-if="carts.length > 0 && old_invoice == 1">
                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="d-flex gap-4 invoice-summary small justify-content-around">
                                            <div>
                                                <div class="mb-1">
                                                    <span>{{lang.customer_name}}: {{customer.name}}</span>
                                                </div>
                                                <div>
                                                    <span>{{lang.customer_phone}}: {{customer.phone}}</span>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="mb-1">
                                                    <span>{{lang.invoice_id}}: {{sell.invoice_id}}</span>
                                                </div>
                                                <div>
                                                    <span>{{lang.date}}: {{sell.sell_date}} </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="table-responsive sell-invoice-table">
                                            <table class="table table-bordered text-center table-sm wiz-table mw-col-width-skip-first">
                                                <thead>
                                                <tr class="bg-secondary text-white">
                                                    <th>{{lang.sl}}</th>
                                                    <th>{{lang.product_title}}</th>
                                                    <th>{{lang.price}}</th>
                                                    <th>{{lang.tax}}</th>
                                                    <th>{{lang.qty}}</th>
                                                    <th>{{lang.total}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr v-for="(cart, index) in carts">
                                                    <td>{{index + 1}}</td>
                                                    <td>{{cart.title}}</td>
                                                    <td>{{appConfig('app_currency')}}{{cart.sell_price | formatNumber}}</td>
                                                    <td>{{appConfig('app_currency')}}{{cart.tax_amount | formatNumber}} <sub>( {{cart.tax_percentage}}% )</sub></td>
                                                    <td>{{cart.quantity}} {{ cart.product.unit ?  cart.product.unit.title : ''}}</td>
                                                    <td>{{appConfig('app_currency')}} {{cart.total_price | formatNumber}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right pr-5">
                                                        {{lang.sub_total_price}}:
                                                    </td>
                                                    <td>
                                                        {{appConfig('app_currency')}}{{summary.sub_total | formatNumber}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5" class="text-right pr-5">
                                                        (-) {{lang.discount}}:
                                                    </td>
                                                    <td>
                                                        {{appConfig('app_currency')}}{{summary.discount | formatNumber}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5" class="text-right pr-5">
                                                        {{lang.net_payable}}
                                                    </td>
                                                    <td>
                                                        {{appConfig('app_currency')}}{{grandTotalotalCartsValue | formatNumber}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5" class="text-right pr-5">
                                                        <strong v-if="summary.payment_type == 1">{{lang.cash_paid}}: </strong>
                                                        <strong v-if="summary.payment_type == 2">{{lang.card_paid}}: </strong>
                                                    </td>
                                                    <td>
                                                        <strong>
                                                            {{appConfig('app_currency')}}{{summary.paid_amount | formatNumber}}
                                                        </strong>
                                                    </td>
                                                </tr>


                                                <tr v-if="summary.due_amount > 0">
                                                    <td colspan="5" class="text-right pr-5">
                                                        {{lang.due_amount}}:
                                                    </td>
                                                    <td>
                                                        {{appConfig('app_currency')}}{{summary.due_amount | formatNumber}}
                                                    </td>
                                                </tr>

                                                <tr v-if="summary.change_amount > 0">
                                                    <td colspan="5" class="text-right pr-5">
                                                        {{lang.change_amount}}:
                                                    </td>
                                                    <td>
                                                        {{appConfig('app_currency')}}{{summary.change_amount | formatNumber}}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row border-top" v-if="updatedInvoice">
                                    <div class="col-md-12 border-bottom-dotted pb-1">
                                        <div class="d-flex pt-1 invoice-summary">
                                            <div class="col-6">
                                                <div>
                                                    <span>{{lang.customer_name}}: {{updated_sell.customer.name}}</span>
                                                </div>
                                                <div class="mtm8">
                                                    <span>{{lang.customer_phone}}: {{updated_sell.customer.phone}}</span>
                                                </div>
                                            </div>
                                            <div class="col-6 text-right">
                                                <div>
                                                    <span>{{lang.invoice_id}}: {{updated_sell.invoice_id}}</span>
                                                </div>
                                                <div class="mtm8">
                                                    <span>{{lang.date}}: {{sell.custome_sell_date}} </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row mb-4 mt-2">
                                            <div class="table-responsive sell-invoice-table">
                                                <table class="table table-bordered text-center" width="100%" cellspacing="0">
                                                    <thead>
                                                    <tr class="bg-secondary text-white">
                                                        <th>{{lang.sl}}</th>
                                                        <th>{{lang.product_title}}</th>
                                                        <th>{{lang.price}}</th>
                                                        <th>{{lang.tax}}</th>
                                                        <th>{{lang.qty}}</th>
                                                        <th>{{lang.total}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <tr v-for="(sell_produt, index) in updated_sell.sell_products">
                                                        <td>{{index + 1}}</td>
                                                        <td>{{sell_produt.product.title}}</td>
                                                        <td>{{appConfig('app_currency')}}{{sell_produt.sell_price | formatNumber}}</td>
                                                        <td>{{appConfig('app_currency')}}{{sell_produt.tax_amount | formatNumber}} <sub>( {{sell_produt.tax_percentage}}% )</sub></td>
                                                        <td>{{sell_produt.quantity}} {{sell_produt.product.unit ? sell_produt.product.unit.title : ''}}</td>
                                                        <td>{{appConfig('app_currency')}}{{sell_produt.total_price | formatNumber}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="text-right pr-5">
                                                            {{lang.sub_total_price}}:
                                                        </td>
                                                        <td>
                                                            {{appConfig('app_currency')}}{{updated_sell.sub_total | formatNumber}}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="5" class="text-right pr-5">
                                                            (-) {{lang.discount}}:
                                                        </td>
                                                        <td>
                                                            {{appConfig('app_currency')}}{{updated_sell.discount | formatNumber}}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="5" class="text-right pr-5">
                                                            {{lang.net_payable}}:
                                                        </td>
                                                        <td>
                                                            {{appConfig('app_currency')}}{{updated_sell.grand_total_price | formatNumber}}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="5" class="text-right pr-5">
                                                            <strong v-if="sell.payment_type == 1">{{lang.cash_paid}}: </strong>
                                                            <strong v-if="sell.payment_type == 2">{{lang.card_paid}}: </strong>
                                                        </td>
                                                        <td>
                                                            <strong>
                                                                {{appConfig('app_currency')}}{{updated_sell.paid_amount | formatNumber}}
                                                            </strong>
                                                        </td>
                                                    </tr>


                                                    <tr v-if="">
                                                        <td colspan="5" class="text-right pr-5">
                                                            {{lang.due_amount}}:
                                                        </td>
                                                        <td>
                                                            {{appConfig('app_currency')}}{{updated_sell.due_amount | formatNumber}}
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div v-if="carts.length > 0 && old_invoice == 1" class="mb-5">


                                    <div class="mb-3">
                                        <table class="table table-bordered wiz-table">
                                            <tbody>
                                            <tr>
                                                <td><strong>{{lang.total}}:</strong></td>
                                                <td class="text-dark">{{appConfig('app_currency')}}{{grandTotalotalCartsValue | formatNumber}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{lang.paid}}:</strong></td>
                                                <td>
                                                    <input type="number" min="0" v-model.number="summary.paid_amount" class="form-control form-control-sm" align="right">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>{{lang.due}}:</strong>
                                                </td>
                                                <td>
                                                    <input type="number" v-model="summary.due_amount = currentDue"  class="form-control form-control-sm text-danger" readonly>
                                                </td>
                                            </tr>
                                            <tr v-if="summary.change_amount > 0">
                                                <td>
                                                    <strong>{{lang.change}}:</strong>
                                                </td>
                                                <td>
                                                    <input type="number" v-model="summary.change_amount" class="form-control font-12" align="right" readonly>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mb-3" v-if="cardInformationArea">
                                        <textarea v-model="summary.card_information"  class="form-control form-control-sm" rows="3" placeholder="Card Information"></textarea>
                                    </div>


                                    <div class="d-flex gap-2 border-top border-bottom my-4 py-3">
                                        <div class="flex-grow-1">
                                            <a href="javasctipt:void(0)" class="btn btn-brand btn-brand-secondary w-100" @click="paymentTypeCash">{{lang.cash}}</a>
                                        </div>
                                        <div class="flex-grow-1">
                                            <a href="javasctipt:void(0)" class="btn btn-brand btn-brand-dark-navy w-100" @click="paymentTypeCard">{{lang.card}}</a>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end" v-if="summary.paid_amount != ''">
                                        <a href="javascript:void(0)" class="btn btn-brand btn-brand-primary btn-block" @click="updateSell()">{{lang.save_and_update}}</a>
                                    </div>
                                </div>

                                <div class="mt-5" v-if="invoicePrintBtn">
                                    <a v-bind:href="'../../export/sell/print-invoice/id='+sell.id" @click="printInvoice" class="btn btn-brand btn-warning w-100" target="_blank">
                                        <i class="fa fa-print pt-2 pb-2 font-40"></i> <br>
                                        <span class="fw-medium">{{lang.print_invoice}}</span>
                                    </a>
                                </div>
                            </div>
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
            const ps = new PerfectScrollbar('.perfect-ps');
            const psForProduct = new PerfectScrollbar('.sell-card-product-scroll');
            const psForCart = new PerfectScrollbar('.sell-cart-scroll');
        },
        name: "EditSell",
        props : {
            sell : Object,
            all_categories : Array,
        },
        data() {
            return {
                lang: [],
                sell_details: Object,
                products: [],
                product: {},
                carts: [],
                categories: this.all_categories,
                category: {},
                customers: [],
                customer: null,
                configs: [],
                my_branch: [],
                updated_sell: [],
                new_customer: {
                    'name': '',
                    'phone': '',
                    'email': '',
                    'address': '',
                },
                summary:{
                    'sub_total': 0,
                    'discount': this.sell.discount,
                    'grand_total': this.sell.grand_total_price,
                    'payment': this.sell.paid_amount,
                    'due_amount': this.sell.due_amount,
                    'paid_amount': this.sell.paid_amount,
                    'change_amount': 0,
                    'payment_type': 1,
                    'card_information': '',
                },
                filter: {
                    search: '',
                    category_id: '',
                },
                createCustomer: false,
                printInvoice:false,
                createPaymentDrawer:false,
                invoicePrintBtn:false,
                cardInformationArea: false,
                updatedInvoice:false,
                old_invoice: 1,
            }
        },

        methods: {
            productFilterByCategory:function(category_id){
                this.filter.category_id = category_id;
            },

            onEnterClick: function() {
                this.products.forEach((product) => {
                    if (product.sku.toLowerCase() == this.filter.search.toLowerCase()){
                        this.addToCart(product.id);
                        this.filter.search = '';
                    }
                });
            },

            addToCart: function(product_id){

                axios.get('../../vue/api/product-available-stock-qty-without-invoice/' + product_id + '/'+this.sell.id).then((response) => {
                    if (response.data > 0){
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
                    }else{
                        toastr["error"]("Stock Out");
                    };
                });
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

            createPayment:function(){
                if (this.sellStoreValidation()) {
                    if (this.carts.length != 0){
                        this.createPaymentDrawer = true;
                    }else {
                        toastr["error"]("!Empty Cart");
                    }
                }
            },

            paymentTypeCard: function() {
                this.cardInformationArea = true;
                this.summary.payment_type = 2;
            },

            paymentTypeCash: function() {
                this.cardInformationArea = false;
                this.summary.payment_type = 1;
            },

            updateSell:function(){
                if (this.sellStoreValidation()){
                    if (this.carts.length != 0){

                        if (this.summary.paid_amount >= 0){
                            axios.patch('../../sell/' + this.sell.id, {carts: JSON.parse(JSON.stringify(this.carts)), customer: JSON.parse(JSON.stringify(this.customer)), summary: this.summary}).then((response) => {
                                this.updated_sell = response.data.sell;
                                this.sell = this.updated_sell;
                                this.sell.custome_sell_date = response.data.sell_date;
                                this.old_invoice = 0;
                                this.updatedInvoice = true,
                                this.invoicePrintBtn = true;
                            }).catch((error) =>{
                                console.error(error);
                            });
                        }else{
                            toastr["error"]("!Paid amount cannot should be negative");
                        }

                    }else {
                        toastr["error"]("!Empty Cart");
                        return false;
                    }
                }
            },

            closeCreatePaymentDrawer: function(){
                this.old_invoice = 1;
                this.createPaymentDrawer = false;
                this.invoicePrintBtn = false;
                this.sell = [];
            },

            newCustomer:function() {
                this.createCustomer = true;
            },

            storeCustomer:function(){
                if (this.customerValidation()){
                    axios.post('../../vue/api/store-customer', {new_customer: JSON.parse(JSON.stringify(this.new_customer))}).then((response) => {
                        this.customer = response.data;
                        this.customers.push(response.data);
                        this.new_customer.name = '';
                        this.new_customer.phone = '';
                        this.new_customer.email = '';
                        this.new_customer.address = '';
                        this.createCustomer = false;
                    }).catch((error) =>{
                        console.error(error);
                    });
                };
            },

            closeDrawer: function () {
                if (this.createCustomer == true) {
                    this.createCustomer = false;
                }
            },

            clearAll:function(){
                this.customer = null;
                this.carts = [];
                this.summary.sub_total = 0;
                this.summary.discount = 0;
                this.summary.grand_total = 0;
                this.summary.payment = 0;
                this.filter.search = '';
            },

            customerValidation:function () {
                let result = false;

                if (this.new_customer.name != ''){
                    result = true;
                }else{
                    toastr["error"]("Customer name is required");
                    result = false
                }

                if (this.new_customer.phone != ''){
                    result = true;
                }else{
                    toastr["error"]("Phone number is required");
                    result = false
                }

                this.customers.forEach((customer) => {
                    if (this.new_customer.phone != ''){
                        if (customer.phone == this.new_customer.phone) {
                            toastr["error"]("Phone number should be Unique");
                            this.new_customer.phone = '';
                            result = false
                        }else{
                            result = true;
                        }
                    }
                });

                return result;
            },

            sellStoreValidation:function () {
                if (this.customer != null){
                    return true;
                }else{
                    alert('Please select a Customer');
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
            subTotalotalCartsValue(){
                this.carts.forEach((element, key) => {
                    axios.get('../../vue/api/product-available-stock-qty-without-invoice/' + element.id + '/'+this.sell.id).then((response) => {
                        if (response.data == 0){
                            this.carts.splice(key, 1)
                        };

                        if (element.quantity > response.data) {
                            toastr["error"]("This quantity is not available");
                            element.quantity = response.data;
                        }
                    });
                });

                let total = 0;
                this.carts.forEach((cart) => {
                    total += cart.total_price;
                });
                return parseFloat((total).toFixed(2));
            },

            grandTotalotalCartsValue(){
                let grand_total = parseFloat(this.subTotalotalCartsValue)  - parseFloat(this.summary.discount);
                return parseFloat((grand_total).toFixed(2));
            },

            currentDue(){
                if(this.summary.paid_amount > this.grandTotalotalCartsValue){
                    let change_amount = parseFloat(this.summary.paid_amount) - parseFloat(this.grandTotalotalCartsValue);
                    this.summary.change_amount = parseFloat((change_amount).toFixed(2));
                    return  0;
                }else{
                    this.summary.change_amount = 0;
                    let current_due =  parseFloat(this.grandTotalotalCartsValue) - parseFloat(this.summary.paid_amount);
                    return parseFloat((current_due).toFixed(2));
                }
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
        },

        beforeMount() {
            axios.get('../../vue/api/products').then((response) => {
                this.products = response.data;
            });

            axios.get('../../vue/api/sell-details/' + this.sell.id).then((response) => {
                this.sell_details = response.data;
                this.customer = this.sell_details.customer;
                this.sell_details.sell_products.forEach((sell_product) => {
                    sell_product.title = sell_product.product.title;
                    sell_product.id = sell_product.product.id;
                    sell_product.price_type = sell_product.product.price_type;
                    sell_product.tax = sell_product.product.tax;
                    this.carts.push(sell_product);
                });
            });

            axios.get('../../vue/api/get-app-configs').then((response) => {
                this.configs = response.data;
            });

            axios.get('../../vue/api/customers').then((response) => {
                this.customers = response.data;
            });

            axios.get('../../vue/api/get-local-lang').then((response) => {
                this.lang = response.data;
            });

            axios.get('../../vue/api/my-branch').then((response) => {
                this.my_branch = response.data;
            });
        }
    }
</script>
