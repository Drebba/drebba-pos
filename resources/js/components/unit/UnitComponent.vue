<template>
    <div class="container-fluid">
        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="page-title">{{lang.units}}</h6>
                <div>
                    <a href="javascript:void(0)" @click="showCreateUnitDrawer" class="btn btn-brand-secondary btn-sm btn-brand"><i class="fa fa-plus"></i> {{lang.create_unit}}</a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center table-sm wiz-table">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th width="5%">{{lang.sl}}</th>
                            <th>{{lang.unit_name}}</th>
                            <th>{{lang.action}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="(unit, index) in units">
                            <td>{{index + 1}}</td>
                            <td>{{unit.title}}</td>
                            <td style="width: 100px">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <a href="javascript:void(0)" class="mx-2 text-brand-primary" @click="editUnit(unit.id)"><i class="bi bi-pencil"></i> </a>
                                    <a href="javascript:void(0);" class="mx-2 text-brand-danger" @click="deleteUnit(unit.id, index)"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer" :class="{show : createUnit}">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">{{lang.create_unit}}</h6>
                    <button class="close" @click="createUnit = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="form-group mb-4">
                        <label class="custom-label">{{lang.unit_name}} <span class="text-danger">*</span></label>
                        <input type="text" autofocus v-model="newUnit.title = unit.title" :placeholder="lang.unit_name" class="form-control">
                        <span class="error" v-if="errors['unit.title']">{{errors['unit.title'][0]}}</span>
                    </div>


                    <div class="form-group text-end">
                        <button type="submit" class="btn btn-brand-primary btn-brand" @click="storeUnit()">{{lang.save}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="drawer shadow right responsive-drawer" :class="{show : editUnitDrower}">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">{{lang.update_unit}}</h6>
                    <button class="close" @click="editUnitDrower = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div class="form-group mb-4">
                        <label class="custom-label">{{lang.unit_name}} <span class="text-danger">*</span></label>
                        <input type="text" autofocus v-model="newUnit.title = unit.title" :placeholder="lang.unit_name" class="form-control">
                        <span class="error" v-if="errors['unit.title']">{{errors['unit.title'][0]}}</span>
                    </div>

                    <div class="form-group text-end">
                        <button type="submit" class="btn btn-brand-primary btn-brand" @click="updateUnit()">{{lang.save}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props : {
            all_units : Array,
        },
        data() {
            return {
                lang: [],
                errors: [],
                units: this.all_units,
                unit: {},
                newUnit: {
                    title: '',
                },
                createUnit: false,
                editUnitDrower: false,
            }
        },

        methods: {
            showCreateUnitDrawer: function(){
                this.errors = [];
                this.createUnit = true;
            },
            storeUnit: function () {
                axios.post('unit', {unit: JSON.parse(JSON.stringify(this.newUnit))}).then((response) => {
                    this.newUnit.title = '';
                    this.createUnit = false;
                    this.units.unshift(response.data);
                    toastr["success"]("Unit successfully added");
                }).catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            },

            editUnit: function (id) {
                this.units.forEach((element) => {
                    if (element.id == id) {
                        this.unit = element;
                    }
                });
                this.errors = [];
                this.editUnitDrower = true;
            },

            updateUnit: function () {
                axios.patch('unit/' + this.unit.id , {unit: JSON.parse(JSON.stringify(this.newUnit))}).then((response) => {
                    this.newUnit.title = '';
                    this.editUnitDrower = false;
                    this.unit = {};
                    toastr["success"]("unit successfully updated");
                }).catch((error) =>{
                    this.errors = error.response.data.errors;
                });
            },

            deleteUnit: function (id, index) {
                swal({
                    title: "Are you sure?",
                    text: "To deleted this Unit",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.delete('unit/' + id).then((response) => {
                            if(response.data[0]=='success'){
                                this.units.splice(index, 1);
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
