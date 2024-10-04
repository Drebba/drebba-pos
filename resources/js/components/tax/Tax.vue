<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="wiz-card">
                    <!-- Card Header - Dropdown -->
                    <div class="wiz-card-header">
                        <h6 class="wiz-card-title">{{lang.taxes}}</h6>
                        <div>
                            <a href="javascript:void(0)" @click="createTaxDeawer()" class="btn btn-brand-secondary btn-sm btn-brand"><i class="fa fa-plus"></i> {{lang.create_tax}}</a>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="wiz-card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center wiz-table">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th width="5%">{{lang.sl}}</th>
                                        <th>{{lang.tax_title}}</th>
                                        <th>{{lang.tax_value}}</th>
                                        <th width="8%">{{lang.action}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <tr v-for="(tax, index) in taxes">
                                    <td>{{index + 1}}</td>
                                    <td>{{tax.title}}</td>
                                    <td>{{tax.value}} %</td>
                                    <td class="font-14">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                            <a href="javascript:void(0)" @click="editTax(tax.id)" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a>
                                            <a href="javascript:void(0);"   @click="deleteTax(tax.id, index)" class="mx-2 text-brand-danger"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer"  :class="{show : createTax}">

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">{{lang.create_tax}}</h6>
                    <button class="close" @click="createTax = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-group">
                            <label class="custom-label">{{lang.tax_title}} <span class="text-danger">*</span></label>
                            <input type="text" autofocus v-model="new_tax.title = tax.title" :placeholder="lang.tax_title" class="form-control">
                            <span class="error" v-if="errors['tax.title']">{{errors['tax.title'][0]}}</span>
                        </div>
                    </div>


                   <div class="mb-4">
                       <label class="custom-label">{{lang.tax_value}} <span class="text-danger">*</span></label>
                       <div class="input-group">
                           <input type="number" v-model:number="new_tax.value = tax.value" :placeholder="lang.tax_value" min="0" max="100"  class="form-control border-end-0">
                           <span class="input-group-text border-start-0 bg-transparent px-3">%</span>
                       </div>
                       <span class="error" v-if="errors['tax.value']">{{errors['tax.value'][0]}}</span>
                   </div>


                    <div class="form-group text-end">
                        <button type="submit" class="btn btn-brand-primary btn-brand" @click="storeTax()">{{lang.save}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer" :class="{show : editTaxDrawer}">

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">{{lang.update_tax}}</h6>
                    <button class="close" @click="closeEditTaxDrawer()"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="custom-label">{{lang.tax_title}} <span class="text-danger">*</span></label>
                                <input type="text" autofocus v-model="new_tax.title = tax.title" :placeholder="lang.tax_title" class="form-control">
                                <span class="error" v-if="errors['tax.title']">{{errors['tax.title'][0]}}</span>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label class="custom-label">{{lang.tax_value}} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" v-model:number="new_tax.value = tax.value" :placeholder="lang.tax_value" min="0" max="100"  class="form-control">
                                <span class="input-group-text">%</span>
                            </div>
                            <span class="error" v-if="errors['tax.value']">{{errors['tax.value'][0]}}</span>
                        </div>
                    </div>
                    <div class="form-group text-end">
                        <button type="submit"  class="btn btn-brand-primary btn-brand" @click="updateTax()">{{lang.save}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Tax",
        props : {
            all_taxes : Array,
        },
        data() {
            return {
                lang: [],
                tax: {},
                taxes: this.all_taxes,
                new_tax: {
                    title: '',
                    value: null,
                },
                errors: [],
                createTax: false,
                editTaxDrawer: false,
            }
        },

        methods: {
            createTaxDeawer:function(){
               this.errors = [];
               this.createTax = true;
            },
            storeTax: function () {
                axios.post('tax', {tax: JSON.parse(JSON.stringify(this.new_tax))}).then((response) => {
                    this.new_tax.title = '';
                    this.new_tax.value = null;
                    this.createTax = false;
                    this.taxes.unshift(response.data);
                    toastr["success"]("Tax successfully added");
                }).catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            },

            editTax: function (id) {
                this.taxes.forEach((element) => {
                    if (element.id == id) {
                        this.tax = element;
                    }
                });
                this.errors = [];
                this.editTaxDrawer = true;
            },

            updateTax: function () {
                axios.patch('tax/' + this.tax.id , {tax: JSON.parse(JSON.stringify(this.new_tax))}).then((response) => {
                    this.new_tax.title = '';
                    this.new_tax.value = null;
                    this.editTaxDrawer = false;
                    this.tax = {};
                    toastr["success"]("Tax successfully updated");
                }).catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            },

            deleteTax: function (id, index) {
                swal({
                    title: "Are you sure?",
                    text: "To deleted this Tax",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.delete('tax/' + id).then((response) => {
                            if(response.data[0]=='success'){
                                this.taxes.splice(index, 1);
                               toastr["success"](response.data[1]);

                            }else{
                                toastr["error"](response.data[1]);

                            }
                        }).catch((error) =>{
                            console.error(error);
                        });
                    }
                });
            },

            closeEditTaxDrawer:function () {
                this.editTaxDrawer = false;
                this.new_tax.title = '';
                this.new_tax.value = null;
            },
        },

        beforeMount() {
            axios.get('vue/api/get-local-lang').then((response) => {
                this.lang = response.data;
            });
        }
    }
</script>
