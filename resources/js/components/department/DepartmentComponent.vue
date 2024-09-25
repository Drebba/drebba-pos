<template>
    <div class="container-fluid">
        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="wiz-card-title">{{lang.departments}}</h6>
                <div>
                    <a href="javascript:void(0)" @click="createDepartmentDrawer" class="btn btn-brand-secondary btn-sm btn-brand"><i class="fa fa-plus me-1"></i> {{lang.create_department}}</a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center wiz-table">
                        <thead>
                        <tr class="bg-secondary text-white">
                                <th style="width: 60px">{{lang.sl}}</th>
                                <th>{{lang.department_name}}</th>
                                <th style="width: 80px">{{lang.action}}</th>
                            </tr>
                        </thead>
                        <tbody>

                        <tr v-for="(department, index) in departments">
                            <td>{{index + 1}}</td>
                            <td>{{department.title}}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <a href="javascript:void(0)" class="mx-2 text-brand-primary" @click="editDepartment(department.id)"><i class="bi bi-pencil"></i> </a>
                                    <a href="javascript:void(0);" class="mx-2 text-brand-danger"   @click="deleteDepartment(department.id, index)"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer" :class="{show: createDepartment}">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="card-title">{{lang.create_department}}</h6>
                    <button class="close" @click="createDepartment = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-group">
                                <label class="custom-label">{{lang.department_name}} <span class="text-danger">*</span></label>
                                <input type="text" autofocus v-model="newDepartment.title = department.title" :placeholder="lang.department_name" class="form-control form-control-lg">
                                <span class="error" v-if="errors['department.title']">{{errors['department.title'][0]}}</span>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group text-end">
                                <button type="submit" class="btn btn-brand-primary btn-brand" @click="storeDepartment()">{{lang.save}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="drawer shadow right" :class="{show : editDepartmentDrower}">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="card-title">{{lang.update_department}}</h6>
                    <button class="close" @click="editDepartmentDrower = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-group">
                                <label class="custom-label">{{lang.department_name}} <span class="text-danger">*</span></label>
                                <input type="text" autofocus v-model="newDepartment.title = department.title" :placeholder="lang.department_name" class="form-control form-control-lg">
                                <span class="error" v-if="errors['department.title']">{{errors['department.title'][0]}}</span>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group text-end">
                                <button type="submit"  class="btn btn-brand-primary btn-brand" @click="updateDepartment()">{{lang.save}}</button>
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
            all_departments : Array,
        },

        data() {
            return {
                errors: [],
                lang: [],
                departments: this.all_departments,
                department: {},
                newDepartment: {
                    title: '',
                },
                createDepartment: false,
                editDepartmentDrower: false,
            }
        },

        methods: {
            createDepartmentDrawer: function(){
                this.errors = [];
                this.createDepartment = true
            },
            storeDepartment: function () {
                axios.post('department', {department: JSON.parse(JSON.stringify(this.newDepartment))}).then((response) => {
                    this.newDepartment.title = '';
                    this.createDepartment = false;
                    this.departments.unshift(response.data);
                    toastr["success"]("Department Successfully Added");
                }).catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            },

            editDepartment: function (id) {
                this.departments.forEach((element) => {
                    if (element.id == id) {
                        this.department = element;
                    }
                });
                this.errors = [];
                this.editDepartmentDrower = true;
            },

            updateDepartment: function () {
                axios.patch('department/' + this.department.id , {department: JSON.parse(JSON.stringify(this.newDepartment))}).then((response) => {
                    this.newDepartment.title = '';
                    this.editDepartmentDrower = false;
                    this.department = {};
                    toastr["success"]("Department Successfully Updated");
                }).catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            },

            deleteDepartment: function (id, index) {
                swal({
                    title: "Are you sure?",
                    text: "To deleted this Department",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.delete('department/' + id).then((response) => {
                            this.departments.splice(index, 1);
                            toastr["success"]("Department Deleted");
                        }).catch((error) =>{
                            toastr["error"]('You can not delete this Department. Employee have this Department.');
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
