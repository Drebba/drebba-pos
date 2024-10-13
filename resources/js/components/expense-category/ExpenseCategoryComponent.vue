<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="wiz-card">
                    <!-- Card Header - Dropdown -->
                    <div class="wiz-card-header">
                        <h6 class="wiz-card-title">{{lang.expense_categories}}</h6>
                        <div>
                            <a href="javascript:void(0)" @click="openCreateDrower" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-plus me-1"></i> {{lang.create_expense_category}} </a>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="wiz-card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center wiz-table">
                                <thead>
                                <tr class="bg-secondary text-white">
                                    <th style="width: 60px">{{lang.sl}}</th>
                                    <th>{{lang.category_name}}</th>
                                    <th width="8%">{{lang.action}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr v-for="(category, index) in categories">
                                    <td>{{index + 1}}</td>
                                    <td>{{category.name}}</td>
                                    <td class="font-14">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                            <a href="javascript:void(0)" class="mx-2 text-brand-primary" @click="editCategory(category.id)"><i class="bi bi-pencil"></i> </a>
                                            <a href="javascript:void(0);" class="mx-2 text-brand-danger" @click="deleteCategory(category.id, index)"><i class="bi bi-trash"></i></a>
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

        <div class="drawer right responsive-drawer" :class="{show : createDrower}">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="card-title">{{lang.create_expense_category}}</h6>
                    <button class="close" @click="createDrower = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="custom-label">{{lang.category_name}} <span class="text-danger">*</span></label>
                                <input type="text" autofocus v-model="newCategory.name = category.name" :placeholder="lang.category_name" class="form-control">
                                <span class="error" v-if="errors['category.name']">{{errors['category.name'][0]}}</span>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-brand-primary btn-brand" @click="storeCategory()">{{lang.save}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="drawer right responsive-drawer" :class="{show : editDrower}">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="card-title">{{lang.update_expense_categories}}</h6>
                    <button class="close" @click="editDrower = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="custom-label">{{lang.category_name}} <span class="text-danger">*</span></label>
                                <input type="text" autofocus v-model="newCategory.name = category.name" :placeholder="lang.category_name" class="form-control">
                                <span class="error" v-if="errors['category.name']">{{errors['category.name'][0]}}</span>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <button type="submit"  class="btn btn-brand-primary btn-brand" @click="updateCategory()">{{lang.save}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props : {
            all_categories : Array,
        },

        data() {
            return {
                lang: [],
                errors: [],
                categories: this.all_categories,
                category: {},
                newCategory: {
                    name: '',
                },
                createDrower: false,
                editDrower: false,
            }
        },

        methods: {

            openCreateDrower:function(){
                this.newCategory.name = '',
                this.errors = [],
                this.createDrower = true
            },
            storeCategory: function () {
                axios.post('expense-category', {category: JSON.parse(JSON.stringify(this.newCategory))}).then((response) => {
                    this.newCategory.name = '';
                    this.createDrower= false;
                    this.categories.unshift(response.data);
                    toastr["success"]("Category Successfully Added");
                    this.errors = [];
                }).catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            },

            editCategory: function (id) {
                this.categories.forEach((element) => {
                    if (element.id == id) {
                        this.category = element;
                    }
                });
                this.errors = [],
                this.editDrower = true;
            },

            updateCategory: function () {
                axios.patch('expense-category/' + this.category.id , {category: JSON.parse(JSON.stringify(this.newCategory))}).then((response) => {
                    this.newCategory.name = '';
                    this.editDrower = false;
                    this.designation = {};
                    toastr["success"]("Category Successfully Updated");
                    this.errors = [];
                }).catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            },

            deleteCategory: function (id, index) {
                swal({
                    title: "Are you sure?",
                    text: "To deleted this Category",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.delete('expense-category/' + id).then((response) => {
                            if(response.data[0]=='success'){
                                this.categories.splice(index, 1);
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
        },

        beforeMount() {
            axios.get('vue/api/get-local-lang').then((response) => {
                this.lang = response.data;
            });
        }
    }
</script>
