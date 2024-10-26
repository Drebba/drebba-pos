<template>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row g-3 sell-pos">
            <div class="col-md-5">
                <div class="sell-card-group">
                    <div class="sell-card-header pb-2 mb-2">
                        <div class="wiz-box p-2">
                            <div class="d-flex gap-2">
                                <div class="flex-grow-1 select-customer">
                                    <v-select :options="customers" v-model="customer" label="name"
                                        placeholder="Select Customer"></v-select>
                                </div>
                                <div>
                                    <div class="form-group">
                                        <button class="btn btn-brand-secondary btn-sm btn-brand" @click="newCustomer()">
                                            <i class="fa fa-plus"></i> </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sell-card-body">
                        <div class="wiz-box d-flex flex-column h-100">
                            <div class="cart-products sell-card position-relative sell-cart-scroll">
                                <h1 v-if="carts.length == 0"
                                    style="text-align: center; color:#d1d1d1;margin-top: 100px">{{ lang.empty_carts }}
                                </h1>
                                <table class="table table-bordered table-sm wiz-table text-12" v-if="carts.length > 0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <span>{{ lang.product_title }}</span>
                                            </th>
                                            <th class="text-center">
                                                <span>{{ lang.sell_price }}</span>
                                            </th>
                                            <th class="text-center"> {{ lang.qty }} </th>
                                            <th class="text-center"> {{ lang.total }} </th>
                                            <th class="text-center"> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(cart, key) in carts">
                                            <td>
                                                <span class="cart-product-title"
                                                    v-if="cart.title.length < 35">{{ cart.title }} </span>
                                                <span class="cart-product-title" v-else>{{
                                                    cart.title.substring(0,35)+".." }} </span>
                                            </td>
                                            <td class="text-center w-100px">
                                                <input type="number" v-model.number="cart.sell_price" step=".1" min=".1"
                                                    value="10" class="form-control form-control-sm text-center"
                                                    v-if="cart.price_type == 1" readonly>
                                                <input type="number" v-model.number="cart.sell_price" step=".1" min=".1"
                                                    value="10" class="form-control form-control-sm text-center"
                                                    v-if="cart.price_type == 2">
                                            </td>
                                            <td class="text-center w-125px">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" v-model.number="cart.quantity" min="1"
                                                        class="form-control form-control-sm text-center">
                                                    <span class="input-group-text bg-transparent" v-if="cart.unit">{{
                                                        cart.unit.title.substring(0,3) }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden" v-model.number="cart.total_price">
                                                <small class="price" style="display: none"> {{ cart.total_price =
                                                cart.quantity * (parseFloat(cart.sell_price) +
                                                    parseFloat(cart.tax_amount))}} </small>
                                                <small class="price"> {{ appConfig('app_currency') }}{{ cart.total_price |
                                                    formatNumber}} </small>
                                            </td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" class="text-brand-danger remove"
                                                    @click="deleteProductFormCart(key)">
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
                                                <td> {{ lang.total }}</td>
                                                <td width="50%">
                                                    <input type="number"
                                                        v-model.number="summary.sub_total = subTotalotalCartsValue"
                                                        class="form-control form-control-sm" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ lang.discount }} </td>
                                                <td>
                                                    <input type="number" v-model="summary.discount"
                                                        class="form-control form-control-sm">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ lang.grand_total }}</td>
                                                <td width="50%">
                                                    <input type="number"
                                                        v-model.number="summary.grand_total_price = grandTotalotalCartsValue"
                                                        class="form-control form-control-sm" readonly>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex gap-1 justify-content-end pe-2">
                                    <div><a href="javascript:void(0)" class="btn btn-brand btn-brand-primary btn-sm"
                                            @click="createPayment()">{{ lang.payment }}</a></div>
                                    <div><a href="javascript:void(0)" v-on:click="archiveDrafts = true"
                                            class="btn btn-brand btn-brand-secondary btn-sm"><i
                                                class="fas fa-boxes"></i> {{ drafts.length }} </a></div>
                                    <div><a href="javascript:void(0)" @click="archiveToDraft()"
                                            class="btn btn-brand btn-brand-warning btn-sm px-3"><i
                                                class="fas fa-grip-lines-vertical"></i> </a></div>
                                    <div><a href="javascript:void(0);" @click="clearAll()"
                                            class="btn btn-brand btn-brand-danger btn-sm"><i
                                                class="bi bi-trash"></i></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="sell-card-group">
                    <div class="sell-card-header">
                        <div class="wiz-box p-2">
                            <div class="input-with-icon">
                                <span class="small input-icon"><i class="bi bi-search"></i></span>
                                <input type="text" v-model="filter.search" v-on:keyup.enter="onEnterClick"
                                    class="form-control form-control-sm" :placeholder="lang.product_search_kye">
                            </div>
                        </div>
                        <div class="perfect-ps category-scrollbox my-2">
                            <div class="filter-category d-flex align-items-center m-n2">
                                <a href="javascript:void(0)" class="filter-category-btn"
                                    :class="{ active: filter.category_id == '' }"
                                    v-on:click="filter.category_id = ''">{{ lang.all }}</a>
                                <a href="javascript:void(0)" class="filter-category-btn" v-for="category in categories"
                                    :class="{ active: filter.category_id == category.id }"
                                    @click="productFilterByCategory(category.id)">{{ category.title }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="sell-card-body sell-card-product-scroll">
                        <div class="p-2">
                            <div class="row g-3 justify-content-center all-products">
                                <div class="col-md-3 col-6" v-for="(product, index) in filteredProduct"
                                    v-if="product.current_stock_quantity > 0">
                                    <div class="single-product" :class="{ selected: isAlreadyInCart(product.id) }"
                                        @click="addToCart(product.id)">
                                        <div class="ratio ratio-16x9">
                                            <div class="single-product-header">
                                                <img :src="'../' + product.thumbnail" class="img-fluid"
                                                    v-if="product.thumbnail != null">
                                                <img :src="'../images/default.png'" class="img-fluid"
                                                    v-if="product.thumbnail == null">
                                            </div>
                                        </div>
                                        <div class="single-product-body">
                                            <div class="d-flex justify-content-between gap-2">
                                                <div>
                                                    <h6 class="single-product-title">
                                                        <span v-if="product.title.length < 15">{{ product.title }}</span>
                                                        <span v-else>{{ product.title.substring(0, 15) + ".." }} </span>
                                                    </h6>
                                                </div>
                                                <div class="single-product-price">
                                                    {{ appConfig('app_currency') }}{{ product.sell_price }}</div>
                                            </div>
                                            <div class="single-sku-price">
                                                <small class="extra-small">Sku: {{ product.sku }}</small>
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
        <div class="drawer shadow right responsive-drawer" :class="{ show: createCustomer }">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="card-title">{{ lang.create_customer }}</h6>
                    <button class="close" @click="closeDrawer()"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="custom-label">{{ lang.name }} <span class="text-danger">*</span></label>
                                <input type="text" v-model="new_customer.name" placeholder="Full Name"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="custom-label">{{ lang.phone }} <span class="text-danger">*</span></label>
                                <input type="text" v-model="new_customer.phone" placeholder="Phone Number"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="custom-label">{{ lang.email }} </label>
                                <input type="email" v-model="new_customer.email" placeholder="Email Address"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="custom-label">{{ lang.address }} </label>
                                <input type="text" v-model.number="new_customer.address" placeholder="Address"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group d-flex justify-content-end">
                                <button type="submit" class="btn btn-brand-primary btn-brand"
                                    @click="storeCustomer()">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="drawer shadow right responsive-drawer drawer-lg" :class="{ show: createPaymentDrawer }">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title">{{ lang.sell_details }}</h6>
                    <button class="close" @click="closeCreatePaymentDrawer()"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body p-0">
                    <div class="text-center bg-soft-primary p-3">
                        <h3 class="company-name fw-medium">{{ appConfig('app_name') }}</h3>
                        <p class="address mb-1">{{ lang.address }}: {{ appConfig('address') }}</p>
                        <p class="vat mb-1">{{ lang.vat_reg_number }} : {{ appConfig('vat_reg_no') }}</p>
                        <p class="outlet mb-0">{{ lang.outlet }}: {{ my_branch.title }} </p>
                    </div>
                    <div class="p-4">
                        <div class="row g-4 gx-lg-5">
                            <div class="col-lg-8 sell-invoice border-md-end">
                                <div class="mb-3" v-if="carts.length > 0">
                                    <div class="border-bottom border-bottom pb-3 mb-4">
                                        <div class="d-flex invoice-summary">
                                            <div class="col-6" v-if="">
                                                <div class="mb-2">
                                                    <span>{{ lang.customer_name }}: {{ customer.name }} </span>
                                                </div>
                                                <div>
                                                    <span>{{ lang.customer_phone }}: {{ customer.phone }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6 text-right">
                                                <div class="mb-2">
                                                    <span>{{ lang.invoice_id }}: ------</span>
                                                </div>
                                                <div>
                                                    <span>{{ lang.date }}: -- -- ---- </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="table-responsive sell-invoice-table">
                                            <table
                                                class="table table-bordered table-sm text-center wiz-table mw-col-width-skip-first">
                                                <thead>
                                                    <tr class="bg-secondary text-white">
                                                        <th>{{ lang.sl }}</th>
                                                        <th>{{ lang.product_title }}</th>
                                                        <th>{{ lang.price }}</th>
                                                        <th>{{ lang.tax }}</th>
                                                        <th>{{ lang.qty }}</th>
                                                        <th>{{ lang.total }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(cart, index) in carts">
                                                        <td>{{ index + 1 }}</td>
                                                        <td>{{ cart.title }}</td>
                                                        <td>{{ appConfig('app_currency') }}{{ cart.sell_price }}</td>
                                                        <td>{{ appConfig('app_currency') }}{{ cart.tax_amount }} <sub>(
                                                                {{ cart.tax_percentage }}% )</sub></td>
                                                        <td>{{ cart.quantity }} {{ cart.unit ? cart.unit.title : '' }}</td>
                                                        <td>{{ appConfig('app_currency') }} {{ cart.total_price |
                                                            formatNumber}} </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="text-end pe-5"> {{ lang.sub_total_price }}:
                                                        </td>
                                                        <td> {{ appConfig('app_currency') }} {{ summary.sub_total |
                                                            formatNumber}} </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="text-end pe-5"> (-) {{ lang.discount }}:
                                                        </td>
                                                        <td> {{ appConfig('app_currency') }}{{ summary.discount |
                                                            formatNumber}} </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="text-end pe-5"> {{ lang.net_payable }}
                                                        </td>
                                                        <td> {{ appConfig('app_currency') }}{{ summary.grand_total_price |
                                                            formatNumber}} </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="text-end pe-5">
                                                            <strong v-if="summary.payment_type == 1">{{ lang.cash_paid }}:
                                                            </strong>
                                                            <strong v-if="summary.payment_type == 2">{{ lang.card_paid }}:
                                                            </strong>
                                                        </td>
                                                        <td>
                                                            <strong> {{ appConfig('app_currency') }}{{ summary.paid_amount
                                                                | formatNumber}} </strong>
                                                        </td>
                                                    </tr>
                                                    <tr v-if="summary.due_amount > 0">
                                                        <td colspan="5" class="text-end pe-5"> {{ lang.due_amount }}:
                                                        </td>
                                                        <td> {{ appConfig('app_currency') }}{{ summary.due_amount |
                                                            formatNumber}} </td>
                                                    </tr>
                                                    <tr v-if="summary.change_amount > 0">
                                                        <td colspan="5" class="text-end pe-5"> {{ lang.change_amount }}:
                                                        </td>
                                                        <td> {{ appConfig('app_currency') }}{{ summary.change_amount |
                                                            formatNumber}} </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-if="sell.invoice_id != null">
                                    <div class="col-md-12 border-bottom-dotted pb-1">
                                        <div class="d-flex pt-1 invoice-summary">
                                            <div class="col-6">
                                                <div class="mb-2">
                                                    <span>{{ lang.customer_name }}: {{ sell.customer.name }}</span>
                                                </div>
                                                <div>
                                                    <span>{{ lang.customer_phone }}: {{ sell.customer.phone }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6 text-end">
                                                <div class="mb-2">
                                                    <span>{{ lang.invoice_id }}: {{ sell.invoice_id }}</span>
                                                </div>
                                                <div>
                                                    <span>{{ lang.date }}: {{ sell.custome_sell_date }} </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div>
                                            <div class="table-responsive sell-invoice-table">
                                                <table class="table table-bordered text-center" width="100%"
                                                    cellspacing="0">
                                                    <thead>
                                                        <tr class="bg-secondary text-white">
                                                            <th>{{ lang.sl }}</th>
                                                            <th>{{ lang.product_title }}</th>
                                                            <th>{{ lang.price }}</th>
                                                            <th>{{ lang.tax }}</th>
                                                            <th>{{ lang.qty }}</th>
                                                            <th>{{ lang.total }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(sell_produtc, index) in sell.sell_products">
                                                            <td>{{ index + 1 }}</td>
                                                            <td>{{ sell_produtc.product.title }}</td>
                                                            <td>{{ appConfig('app_currency') }}{{ sell_produtc.sell_price |
                                                                formatNumber}}</td>
                                                            <td>{{ appConfig('app_currency') }}{{ sell_produtc.tax_amount |
                                                                formatNumber}} <sub>( {{ sell_produtc.tax_percentage }}%
                                                                    )</sub></td>
                                                            <td>{{ sell_produtc.quantity }} {{ sell_produtc.product.unit ?
                                                                sell_produtc.product.unit.title : ''}}</td>
                                                            <td>{{ appConfig('app_currency') }}{{ (sell_produtc.total_price) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" class="text-right pr-5">
                                                                {{ lang.sub_total_price }}: </td>
                                                            <td> {{ appConfig('app_currency') }}{{ sell.sub_total |
                                                                formatNumber}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" class="text-right pr-5"> (-)
                                                                {{ lang.discount }}: </td>
                                                            <td> {{ appConfig('app_currency') }}{{ sell.discount |
                                                                formatNumber}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" class="text-right pr-5">
                                                                {{ lang.net_payable }}: </td>
                                                            <td> {{ appConfig('app_currency') }}{{ sell.grand_total_price |
                                                                formatNumber}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" class="text-right pr-5">
                                                                <strong
                                                                    v-if="sell.payment_type == 1">{{ lang.cash_paid }}:
                                                                </strong>
                                                                <strong
                                                                    v-if="sell.payment_type == 2">{{ lang.card_paid }}:
                                                                </strong>
                                                            </td>
                                                            <td>
                                                                <strong> {{ appConfig('app_currency') }}{{ sell.paid_amount
                                                                    | formatNumber}} </strong>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="">
                                                            <td colspan="5" class="text-right pr-5">
                                                                {{ lang.due_amount }}: </td>
                                                            <td> {{ appConfig('app_currency') }}{{ sell.due_amount |
                                                                formatNumber}} </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div v-if="carts.length > 0" class="mb-5">
                                    <div class="mb-3">
                                        <table class="table table-bordered wiz-table">
                                            <tbody>
                                                <tr>
                                                    <td><strong>{{ lang.total }}:</strong></td>
                                                    <td class="text-dark">
                                                        {{ appConfig('app_currency') }}{{ grandTotalotalCartsValue }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ lang.paid }}:</strong></td>
                                                    <td>
                                                        <input type="number" min="0"
                                                            v-model.number="summary.paid_amount"
                                                            class="form-control form-control-sm" align="right">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>{{ lang.due }}:</strong>
                                                    </td>
                                                    <td>
                                                        <input type="number" v-model="summary.due_amount = currentDue"
                                                            class="form-control form-control-sm text-danger" readonly>
                                                    </td>
                                                </tr>
                                                <tr v-if="summary.change_amount > 0">
                                                    <td>
                                                        <strong>{{ lang.change }}:</strong>
                                                    </td>
                                                    <td>
                                                        <input type="number" v-model="summary.change_amount"
                                                            class="form-control font-12" align="right" readonly>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mb-3" v-if="cardInformationArea">
                                        <textarea v-model="summary.card_information" class="form-control small" rows="3"
                                            placeholder="Card Information"></textarea>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <div class="flex-grow-1"><a href="javasctipt:void(0)"
                                                class="btn btn-brand-secondary w-100"
                                                @click="paymentTypeCash">{{ lang.cash }}</a></div>
                                        <div class="flex-grow-1"><a href="javasctipt:void(0)"
                                                class="btn btn-brand-dark-navy w-100"
                                                @click="paymentTypeCard">{{ lang.card }}</a></div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="javascript:void(0)" class="btn btn-brand btn-brand-primary w-100"
                                            v-if="!this.isSellStoreProcessing"
                                            @click="storeSell()">{{ lang.confirm_payment }}</a>
                                        <a href="javascript:void(0)" class="btn btn-brand btn-brand-primary w-100"
                                            v-if="this.isSellStoreProcessing">
                                            <div class="spinner-border text-danger" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-5" v-if="sell.invoice_id != null">
                                    <a @click="printInvoice" class="btn btn-brand-warning btn-brand w-100"
                                        target="_blank">
                                        <i class="fa fa-print me-2"></i> <br>
                                        <strong>{{ lang.print_invoice }}</strong>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="drawer shadow right responsive-drawer" :class="{ show: archiveDrafts }">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="card-title">{{ lang.draft }}</h6>
                    <button class="close" v-on:click="archiveDrafts = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <input type="text" v-model="filter.draft_search_key" autofocus class="form-control"
                            :placeholder="lang.draft_search_key">
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row mb-2" v-for="(draft, index)  in filteredDratfs">
                                <div class="col-md-11">
                                    <a href="javascript:void(0)"
                                        class="list-group-item list-group-item-action rounded-0"
                                        @click="selectDraft(index, draft.id)"> {{ draft.inquiry_id }},
                                        {{ draft.customer.name }} , {{ draft.customer.phone }} </a>
                                </div>
                                <div class="col-md-1" @click="deleteDraft(index, draft.id)">
                                    <a href="jabascript:void(0)"><i class="bi bi-trash text-danger"></i> </a>
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
    name: "NewSell",
    props: {
        all_categories: Array,
    },
    data() {
        return {
            lang: [],
            sell: [],
            products: [],
            all_tables:[],
            product: {},
            carts: localStorage.getItem('carts') ?? [],
            categories: this.all_categories,
            category: {},
            customers: [],
            customer: null,
            configs: [],
            my_branch: [],
            drafts: [],
            new_customer: {
                'name': '',
                'phone': '',
                'email': '',
                'address': '',
            },
            summary: {
                'sub_total': 0,
                'discount': 0,
                'grand_total_price': 0,
                'paid_amount': 0,
                'due_amount': 0,
                'change_amount': 0,
                'payment_type': 1,
                'card_information': '',
            },
            draft: {
                'draft_key': 0,
                'draft_id': 0,
            },
            filter: {
                search: '',
                draft_search_key: '',
                category_id: '',
            },
            createCustomer: false,
            createPaymentDrawer: false,
            cardInformationArea: false,
            archiveDrafts: false,
            isSellStoreProcessing: false,
        }
    },

    methods: {
        productFilterByCategory: function (category_id) {
            this.filter.category_id = category_id;
        },

        onEnterClick: function () {
            this.products.forEach((product) => {
                if (product.sku.toLowerCase() == this.filter.search.toLowerCase()) {
                    this.addToCart(product.id);
                    this.filter.search = '';
                }
            });
        },

        addToCart: function (product_id) {
            if (this.isAlreadyInCart(product_id)) {
                this.carts.forEach((cart) => {
                    if (cart.id == product_id) {
                        cart.quantity = cart.quantity + 1;
                    }
                });
            } else {
                this.products.forEach((product) => {
                    if (product.id == product_id) {
                        this.product = product;
                        this.product.quantity = 1;
                        this.carts.unshift(this.product);
                        sessionStorage.setItem('carts')
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

        createPayment: function () {
            if (this.sellStoreValidation()) {
                if (this.carts.length != 0) {
                    this.createPaymentDrawer = true;
                    if (this.archiveDrafts == true) {
                        this.archiveDrafts = false;
                    }
                } else {
                    toastr["error"]("!Empty Cart");
                }
            }
        },

        storeSell: function () {
            if (this.sellStoreValidation()) {
                if (this.carts.length != 0) {
                    if (this.summary.paid_amount >= 0) {
                        this.isSellStoreProcessing = true;

                        axios.post('../vue/api/store-sell', { carts: JSON.parse(JSON.stringify(this.carts)), customer: JSON.parse(JSON.stringify(this.customer)), summary: this.summary }).then((response) => {
                            this.sell = response.data.sell;
                            this.sell.custome_sell_date = response.data.sell_date;
                            if (this.draft.draft_id != '') {
                                this.deleteDraft(this.draft.draft_key, this.draft.draft_id);
                            }
                            this.clearAll();
                            this.invoicePrintBtn = true;
                            this.isSellStoreProcessing = false;

                        }).catch((error) => {
                            console.error(error);
                            this.isSellStoreProcessing = false;
                        });
                    } else {
                        toastr["error"]("!Paid amount cannot should be negative");
                    }
                } else {
                    toastr["error"]("!Empty Cart");
                }
            }
        },

        printInvoice: function () {
            axios.get('../export/sell/print-invoice/id=' + this.sell.id)
                .then((response) => {
                    const printableContent = response.data;
                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none'; // Keep the iframe hidden
                    document.body.appendChild(iframe);

                    // Get the iframe document
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

                    // Write the printable content to the iframe
                    iframeDoc.open();
                    iframeDoc.write(printableContent);
                    iframeDoc.close();

                    // Trigger the print dialog from the iframe
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();

                    // Remove the iframe after printing or canceling
                    iframe.contentWindow.onafterprint = function () {
                        document.body.removeChild(iframe);
                    };
                })
                .catch((error) => {
                    console.error('Error printing invoice:', error);
                })
                .finally(() => {
                    this.createPaymentDrawer = false;
                    this.invoicePrintBtn = false;
                    this.sell = []; // Reset sell data after printing
                });
        },


        archiveToDraft: function () {
            if (this.customer != null) {
                if (this.carts.length != 0) {
                    axios.post('../vue/api/store-draft', { carts: JSON.parse(JSON.stringify(this.carts)), customer: JSON.parse(JSON.stringify(this.customer)), summary: this.summary }).then((response) => {
                        this.drafts.unshift(response.data)
                        this.clearAll();
                    }).catch((error) => {
                        console.error(error);
                    });
                } else {
                    toastr["error"]("!Empty Cart");
                    return false;
                }
            } else {
                toastr["error"]("Please select a Customer");
                return false;
            }

        },

        selectDraft: function (index, draft_id) {
            this.carts = [];
            this.drafts.forEach((element) => {
                if (element.id == draft_id) {
                    element.draft_products.forEach((draft_product) => {
                        console.log(draft_product.product.price_type)
                        draft_product.title = draft_product.product.title;
                        draft_product.id = draft_product.product.id;
                        draft_product.price_type = draft_product.product.price_type;
                        draft_product.tax = draft_product.product.tax;
                        this.carts.push(draft_product);
                    });
                }
            });

            this.draft.draft_key = index;
            this.draft.draft_id = draft_id;
        },

        deleteDraft: function (key, draft_id) {
            this.drafts.splice(key, 1);
            axios.get('../vue/api/delete-drafts/' + draft_id).then((response) => {
                console.log(response.data)
            });
        },

        newCustomer: function () {
            this.createCustomer = true;
            this.archiveDrafts = false;
            this.createPaymentDrawer = false
        },

        storeCustomer: function () {
            if (this.customerValidation()) {
                axios.post('../vue/api/store-customer', { new_customer: JSON.parse(JSON.stringify(this.new_customer)) }).then((response) => {
                    this.customer = response.data;
                    this.customers.push(response.data);
                    this.new_customer.name = '';
                    this.new_customer.phone = '';
                    this.new_customer.email = '';
                    this.new_customer.address = '';
                    this.createCustomer = false;
                }).catch((error) => {
                    console.error(error);
                });
            };
        },

        closeDrawer: function () {
            if (this.createCustomer == true) {
                this.createCustomer = false;
            }
        },

        closeCreatePaymentDrawer: function () {
            this.createPaymentDrawer = false;
            this.invoicePrintBtn = false;
            this.sell = [];
        },

        paymentTypeCard: function () {
            this.cardInformationArea = true;
            this.summary.payment_type = 2;
        },

        paymentTypeCash: function () {
            this.cardInformationArea = false;
            this.summary.payment_type = 1;
        },

        clearAll: function () {
            this.carts = [];
            this.summary.sub_total = 0;
            this.summary.discount = 0;
            this.summary.grand_total_price = 0;
            this.summary.paid_amount = 0;
            this.summary.due_amount = 0;
            this.summary.change_amount = 0;
            this.summary.payment_type = 1;
            this.summary.card_information = '';
            this.filter.search = '';
            this.draft.draft_id = '';
            this.draft.draft_key = '';

            this.configs.forEach((element) => {
                if (element.option_key == 'default_customer') {
                    this.customers.forEach((customer) => {
                        if (customer.id == element.option_value) {
                            this.customer = customer;
                        }
                    });
                }
            });
        },

        customerValidation: function () {
            let result = false;

            if (this.new_customer.name != '') {
                result = true;
            } else {
                toastr["error"]("Customer name is required");
                result = false
            }

            if (this.new_customer.phone != '') {
                result = true;
            } else {
                toastr["error"]("Phone number is required");
                result = false
            }

            this.customers.forEach((customer) => {
                if (this.new_customer.phone != '') {
                    if (customer.phone == this.new_customer.phone) {
                        toastr["error"]("Phone number should be Unique");
                        this.new_customer.phone = '';
                        result = false
                    } else {
                        result = true;
                    }
                }
            });

            return result;
        },

        sellStoreValidation: function () {
            if (this.customer != null) {
                return true;
            } else {
                toastr["error"]("Please select a Customer");
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
        subTotalotalCartsValue() {
            this.carts.forEach((element, key) => {
                axios.get('../vue/api/product-available-stock-qty/' + element.id).then((response) => {
                    if (response.data == 0) {
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

        grandTotalotalCartsValue() {
            if (this.summary.discount > this.subTotalotalCartsValue) {
                toastr["error"]("Discount amount should be smaller than sub subtotal price");
                this.summary.discount = 0;
            }

            let grand_total = parseFloat(this.subTotalotalCartsValue) - parseFloat(this.summary.discount);
            this.summary.paid_amount = parseFloat((grand_total).toFixed(2));
            return parseFloat((grand_total).toFixed(2));
        },

        currentDue() {
            if (this.summary.paid_amount > this.grandTotalotalCartsValue) {
                let change_amount = parseFloat(this.summary.paid_amount) - parseFloat(this.grandTotalotalCartsValue);
                this.summary.change_amount = parseFloat((change_amount).toFixed(2));
                return 0;
            } else {
                this.summary.change_amount = 0;
                let current_due = parseFloat(this.grandTotalotalCartsValue) - parseFloat(this.summary.paid_amount);
                return parseFloat((current_due).toFixed(2));
            }
        },

        filteredProduct: function () {
            if (this.filter.category_id != '') {
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

        filteredDratfs: function () {
            if (this.filter.draft_search_key != '') {
                return this.drafts.filter((draft) => {
                    return draft.inquiry_id.toLowerCase().match(this.filter.draft_search_key.toLowerCase())
                        || draft.customer.name.toLowerCase().match(this.filter.draft_search_key.toLowerCase())
                        || draft.customer.phone.toLowerCase().match(this.filter.draft_search_key.toLowerCase())
                });
            }
            return this.drafts;
        },
    },

    beforeMount() {
        axios.get('../vue/api/products').then((response) => {
            this.products = response.data;
        });

        axios.get('../vue/api/get-local-lang').then((response) => {
            this.lang = response.data;
        });

        axios.get('../vue/api/get-app-configs').then((response) => {
            this.configs = response.data;
        });

        axios.get('../vue/api/customers').then((response) => {
            this.customers = response.data;
            this.configs.forEach((element) => {
                if (element.option_key == 'default_customer') {
                    this.customers.forEach((customer) => {
                        if (customer.id == element.option_value) {
                            this.customer = customer;
                        }
                    });
                }
            });
        });

        axios.get('../vue/api/my-branch').then((response) => {
            this.my_branch = response.data;
        });

        axios.get('../vue/api/drafts').then((response) => {
            this.drafts = response.data;
        });
    }
}
</script>
