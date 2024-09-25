<template>
    <!-- Begin Page Content -->
    <div class="container-fluid pl-2 pr-2">
        <div class="row sell-pos" v-if="updateRequisitionSection">
            <div class="col-md-12 pr-0">
                <div class="card mb-2 rounded-0">
                    <div class="card-header bg-secondary rounded-0">
                        <div class="row text-right">
                            <h5 class="text-white pt-2" > {{lang.requisition_review_check_send}}</h5>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body p-0 text-right">
                        <div class="cart-products pr-2 pt-2 scroll-bar pb-2" style="min-height: 70vh">
                            <div class="row sell-item border-top pt-2 pb-2" v-for="(cart, key) in carts">
                                <div class="col-md-4 pl-4 text-left">
                                    <div class="img">
                                        <img :src="'../'+cart.product.thumbnail"  v-if="cart.product.thumbnail != null" height="70">
                                        <img :src="'../images/default.png'"  v-if="cart.product.thumbnail == null" height="70">
                                    </div>
                                </div>

                                <div class="col-md-4 pl-4 text-left">
                                    <span class="product-title" style="font-size: 14px">{{cart.title}} </span>
                                    <br> <span class="product-title">{{cart.product.sku}} </span>
                                </div>

                                <div class="col-md-3">
                                    <div class="float-right">
                                        <div class="input-group mb-3">
                                            <input type="number" v-model.number="cart.quantity" min="1" value="10" class="form-control font-12 text-center">
                                            <div class="input-group-append" style="height: 32px">
                                                <span class="input-group-text" id=''>{{ cart.product.unit ? cart.product.unit.title : '--'}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <a href="javascript:void(0)" class="mr-2 text-danger remove" @click="deleteProductFormCart(key)" style="font-size: 16px">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="cart-footer">
                            <div class="row">

                                <div class="col-md-12">
                                    <a href="javascript:void(0)" class="btn btn-primary btn-block btn-sm" @click="updateRequisition()">{{lang.send}}</a>
                                </div>

                                <div class="col-md-12 mt-2">
                                    <a href="javascript:void(0)" class="btn btn-danger btn-block btn-sm" @click="rejectRequisition()">{{lang.reject}}</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="drawer shadow right responsive-drawer" v-if="rejectNoteArea">
            <button class="btn btn-primary btn-close" @click="rejectNoteArea = false">x</button>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reject Note</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea v-model="reject_note" placeholder="Short Note" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12 pull-right">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block" @click="confirmRejectRequsition()">{{lang.save}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row" v-if="printInvoice">
            <div class="col-12" v-if="requisition">
                <div class="card mb-4 rounded-0">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Requisition  Details</h6>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                            <a v-bind:href="'../export/requisition/print-invoice/id='+requisition.id+'/type={print}'" target="_blank" class="btn btn-warning rounded-0 btn-sm">
                                <i class="fa fa-print"></i> {{lang.print_invoice}}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <table class="table table-bordered table-sm text-center">
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
                            <div class="col-md-4">
                                <table class="table table-bordered table-sm text-center">
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
                            <div class="col-md-4">
                                <table class="table table-bordered table-sm text-center">
                                    <thead>
                                    <tr class="bg-secondary text-white">
                                        <th colspan="2">{{lang.requisition_summary}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Invoice Id</td>
                                        <td>RE000007</td>
                                    </tr>

                                    <tr>
                                        <td>Created Date</td>
                                        <td>2019-09-12</td>
                                    </tr>

                                    <tr>
                                        <td>Status</td>
                                        <td class="p-0">
                                            <label class="btn btn-warning btn-sm btn-block m-0" v-if="requisition.status == 0"><b>{{lang.pending}}</b></label>
                                            <label class="btn btn-info btn-sm btn-block m-0" v-if="requisition.status == 1"><b>{{lang.delivered}}</b></label>
                                            <label class="btn btn-success btn-sm btn-block m-0" v-if="requisition.status == 2"><b>{{lang.complete}}</b></label>
                                            <label class="btn btn-danger btn-sm btn-block m-0" v-if="requisition.status == 3"><b>{{lang.rejected}}</b></label>
                                            <label class="btn btn-danger btn-sm btn-block m-0" v-if="requisition.status == 4"><b>{{lang.canceled}}</b></label>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center" width="100%" cellspacing="0">
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
                                    <td>{{requisition_product.quantity}} {{ requisition_product.product.unit ? requisition_product.product.unit.title : ''}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12" v-if="requisition.reject_note">
                            <p><b>Reject Note</b>: {{requisition.reject_note }}</p>
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
        name: "ShowRequisition",
        props : {
            requisition : Object,
        },
        data() {
            return {
                carts: [],
                lang: [],
                reject_note: '',
                printInvoice:false,
                updateRequisitionSection:true,
                rejectNoteArea:false,
            }
        },

        methods: {
            deleteProductFormCart: function (key) {
                this.carts.splice(key, 1)
            },

            updateRequisition:function(){
                if (this.carts.length != 0){
                    axios.post('../vue/api/update-requisition-to-confirm', {carts: JSON.parse(JSON.stringify(this.carts)), requisition: this.requisition}).then((response) => {
                        this.requisition = response.data;
                        this.printInvoice = true;
                        this.updateRequisitionSection = false;
                    }).catch((error) =>{
                        console.error(error);
                    });
                }else {
                    alert('!Empty Cart')
                }
            },

            rejectRequisition:function(){
                this.rejectNoteArea = true;
            },

            confirmRejectRequsition(){
                this.rejectNoteArea = false;

                if (this.reject_note != ''){
                    axios.post('../vue/api/reject-requisition', {reject_note: JSON.parse(JSON.stringify(this.reject_note)), requisition: this.requisition}).then((response) => {
                        this.requisition = response.data;
                        this.printInvoice = true;
                        this.updateRequisitionSection = false;
                    }).catch((error) =>{
                        console.error(error);
                    });
                }else{
                    alert('Please Type Note');
                }
            }
        },

        computed: {
            //
        },

        beforeMount() {
            axios.get('../vue/api/requisition-details/' + this.requisition.id).then((response) => {
                response.data.requisition_products.forEach((requisition_product) => {
                    requisition_product.title = requisition_product.product.title;
                    requisition_product.id = requisition_product.product.id;
                    this.carts.push(requisition_product);
                });
            });

            axios.get('../vue/api/get-local-lang').then((response) => {
                this.lang = response.data;
            });
        }
    }
</script>
