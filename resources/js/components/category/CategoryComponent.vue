<template>
    <div class="container-fluid">
        <div class="row g-3">
            <div class="col-md-12">
                <div class="wiz-card">
                    <!-- Card Header - Dropdown -->
                    <div class="wiz-card-header">
                        <h6 class="page-title">{{lang.categories}}</h6>
                        <div>
                            <a href="javascript:void(0)" @click="showCreateCategoryDrawer" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-plus me-1"></i> {{lang.create_category}}</a>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="wiz-card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered wiz-table mw-col-width-skip-first">
                                <thead>
                                <tr class="bg-secondary text-white">
                                    <th width="5%">{{lang.sl}}</th>
                                    <th>{{lang.category_name}}</th>
                                    <th class="text-center" width="10%">{{lang.action}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr v-for="(category, index) in categories">
                                    <td>{{index + 1}}</td>
                                    <td>{{category.title}}</td>
                                    <td class="text-center">
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

        <div class="drawer shadow right responsive-drawer" :class="{show : createCategory}">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">{{lang.create_category}}</h6>
                    <button class="close" @click="createCategory = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div>
                        <div class="form-group mb-4">
                            <label class="custom-label">{{lang.category_name}} <span class="text-danger">*</span></label>
                            <input type="text" autofocus v-model="newCategory.title = category.title" :placeholder="lang.category_name" class="form-control">
                            <span class="error" v-if="errors['category.title']">{{errors['category.title'][0]}}</span>
                        </div>


                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-brand-primary btn-brand" @click="storeCategory()">{{lang.save}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer" :class="{show : editCategoryDrower}">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">{{lang.update_category}}</h6>
                    <button class="close" @click="editCategoryDrower = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div>
                        <div class="form-group mb-4">
                            <label class="custom-label">{{lang.category_name}} <span class="text-danger">*</span></label>
                            <input type="text" autofocus v-model="newCategory.title = category.title" :placeholder="lang.category_name" class="form-control">
                            <span class="error" v-if="errors['category.title']">{{errors['category.title'][0]}}</span>
                        </div>


                        <div class="form-group text-end">
                            <button type="submit"  class="btn btn-brand btn-brand-primary" @click="updateCategory()">{{lang.save}}</button>
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
                    title: '',
                },
                createCategory: false,
                editCategoryDrower: false,
            }
        },

        methods: {
            showCreateCategoryDrawer: function(){
                this.errors = [];
                this.createCategory = true;
            },
            storeCategory: function () {
                axios.post('category', {category: JSON.parse(JSON.stringify(this.newCategory))}).then((response) => {
                    this.newCategory.title = '';
                    this.createCategory = false;
                    this.categories.unshift(response.data);
                    toastr["success"]("Category successfully added");
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
                this.errors = [];
                this.editCategoryDrower = true;
            },

            updateCategory: function () {
                axios.patch('category/' + this.category.id , {category: JSON.parse(JSON.stringify(this.newCategory))}).then((response) => {
                    this.newCategory.title = '';
                    this.editCategoryDrower = false;
                    this.category = {};
                    toastr["success"]("Category successfully updated");
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
                        axios.delete('category/' + id).then((response) => {
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
