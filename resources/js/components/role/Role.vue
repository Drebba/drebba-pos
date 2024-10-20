<template>
    <div class="container-fluid">
        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="wiz-card-title">Role</h6>
                <div>
                    <a href="javascript:void(0)" @click="createRole = true" class="btn btn-brand-secondary btn-sm btn-brand"><i class="fa fa-plus me-1"></i> {{lang.create_role}}</a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center wiz-table">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th style="width: 60px">{{lang.sl}}</th>
                            <th>{{lang.role_name}}</th>
                            <th  style="width: 80px">{{lang.action}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="(role, index) in roles">
                            <td>{{index + 1}}</td>
                            <td>{{role.name}}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <a href="javascript:void(0)" class="mx-2 text-brand-primary" @click="editRole(role.id)"><i class="bi bi-pencil"></i> </a>
                                    <a href="javascript:void(0);" class="mx-2 text-brand-danger"  @click="deleteRole(role.id, index)" v-if="role.id != 1"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer" :class="{show : createRole}">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="card-title">{{lang.create_role}}</h6>
                    <button class="close" @click="createRole = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-group">
                                <label class="custom-label">{{lang.role_name}} <span class="text-danger">*</span></label>
                                <input type="text" autofocus v-model="newRole.name = role.name" :placeholder="lang.role_name" class="form-control form-control-lg">
                                <span class="error" v-if="errors['role.name']">{{errors['role.name'][0]}}</span>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group text-end">
                                <button type="submit" class="btn btn-brand-primary btn-brand" @click="storeRole()">{{lang.save}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer" :class="{show:editRoleDrower}">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">{{lang.update_role}}</h6>
                    <button class="close" @click="editRoleDrower = false">x</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-group">
                                <label class="custom-label">{{lang.role_name}} <span class="text-danger">*</span></label>
                                <input type="text" autofocus v-model="newRole.name = role.name"  class="form-control form-control-lg">
                                <span class="error" v-if="errors['role.name']">{{errors['role.name'][0]}}</span>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group text-end">
                                <button type="submit"  class="btn btn-primary btn-block" @click="updateRole()">{{lang.save}}</button>
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
            all_roles : Array,
        },
        data() {
            return {
                lang: [],
                errors: [],
                roles: this.all_roles,
                role: {},
                newRole: {
                    name: '',
                },
                createRole: false,
                editRoleDrower: false,
            }
        },

        methods: {
            storeRole: function () {
                axios.post('role', {role: JSON.parse(JSON.stringify(this.newRole))}).then((response) => {
                    console.log(response.data);
                    this.newRole.name = '';
                    this.createRole = false;
                    this.roles.unshift(response.data);
                    toastr["success"]("Role successfully added");
                }).catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            },

            editRole: function (id) {
                this.roles.forEach((element) => {
                    if (element.id == id) {
                        this.role = element;
                    }
                });
                this.editRoleDrower = true;
            },

            updateRole: function () {
                axios.patch('role/' + this.role.id , {role: JSON.parse(JSON.stringify(this.newRole))}).then((response) => {
                    this.newRole.name = '';
                    this.editRoleDrower = false;
                    this.role = {};
                    toastr["success"]("Role successfully updated");
                }).catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            },

            deleteRole: function (id, index) {
                swal({
                    title: "Are you sure?",
                    text: "To deleted this Category",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.delete('role/' + id).then((response) => {
                            this.roles.splice(index, 1);
                            toastr["success"]("Role has been deleted");
                        }).catch((error) =>{
                            console.error(error);
                            toastr["error"]("Role not  deleted");

                        });
                    }
                });
            },

            roleValidationUpdate:function () {
                let result = false;

                if (this.newRole.name != ''){
                    this.roles.forEach((element) => {
                        if (this.role.id != element.id) {
                           if (element.name == this.newRole.name) {
                                toastr["error"]("Role name is already exist");
                                this.newRole.name = '';
                                this.role.name = '';
                                result = false
                            }else{
                                result = true;
                            }
                        }
                    });
                }else{
                    toastr["error"]("Role name is required");
                    result = false
                }


                return result;
            },
        },

        beforeMount() {
            axios.get('vue/api/get-local-lang').then((response) => {
                this.lang = response.data;
            });
        }

    }
</script>
