<template>
    <div class="container-fluid">
        <div class="row g-3">
            <div class="col-md-12">
                <div class="wiz-card">
                    <!-- Card Header - Dropdown -->
                    <div class="wiz-card-header">
                        <h6 class="page-title">Tables</h6>
                        <div>
                            <a href="javascript:void(0)" @click="showCreateTableDrawer" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-plus me-1"></i> Create Table</a>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="wiz-card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered wiz-table mw-col-width-skip-first">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th width="5%">sl</th>
                                        <th>Table name</th>
                                        <th class="text-center" width="10%">{{lang.action}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(tableItem, index) in tables" :key="tableItem.id">
                                        <td>{{index + 1}}</td>
                                        <td>{{tableItem.name}}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                <a href="javascript:void(0)" class="mx-2 text-brand-primary" @click="editTable(tableItem.id)"><i class="bi bi-pencil"></i></a>
                                                <a href="javascript:void(0);" class="mx-2 text-brand-danger" @click="deleteTable(tableItem.id, index)"><i class="bi bi-trash"></i></a>
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

        <!-- Drawer for Adding a Table -->
        <div class="drawer shadow right responsive-drawer" :class="{show : createTable}">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Add Table</h6>
                    <button class="close" @click="createTable = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div>
                        <div class="form-group mb-4">
                            <label class="custom-label">Table name <span class="text-danger">*</span></label>
                            <input type="text" autofocus v-model="newTable.name" placeholder="Enter table name" class="form-control">
                            <span class="error" v-if="errors['table.name']">{{errors['table.name'][0]}}</span>
                        </div>

                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-brand-primary btn-brand" @click="storeTable()">{{lang.save}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Drawer for Editing a Table -->
        <div class="drawer shadow right responsive-drawer" :class="{show : editTableDrawer}">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Edit Table</h6>
                    <button class="close" @click="editTableDrawer = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body">
                    <div>
                        <div class="form-group mb-4">
                            <label class="custom-label">Table name <span class="text-danger">*</span></label>
                            <input type="text" autofocus v-model="selectedTable.name" placeholder="Enter table name" class="form-control">
                            <span class="error" v-if="errors['table.name']">{{errors['table.name'][0]}}</span>
                        </div>

                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-brand btn-brand-primary" @click="updateTable()">{{lang.save}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        all_tables: Array,
    },
    data() {
        return {
            lang: [],
            errors: [],
            tables: this.all_tables,
            selectedTable: {},
            newTable: {
                name: '',
            },
            createTable: false,
            editTableDrawer: false,
        }
    },

    methods: {
        showCreateTableDrawer() {
            this.errors = [];
            this.createTable = true;
        },

        storeTable() {
            axios.post('table', { table: JSON.parse(JSON.stringify(this.newTable)) })
                .then((response) => {
                    this.newTable.name = '';
                    this.createTable = false;
                    this.tables.unshift(response.data);
                    toastr["success"]("Table successfully added");
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
        },

        editTable(id) {
            this.tables.forEach((element) => {
                if (element.id === id) {
                    this.selectedTable = element;
                }
            });
            this.errors = [];
            this.editTableDrawer = true;
        },

        updateTable() {
            axios.patch('table/' + this.selectedTable.id, { table: JSON.parse(JSON.stringify(this.selectedTable)) })
                .then(() => {
                    this.editTableDrawer = false;
                    this.selectedTable = {};
                    toastr["success"]("Table successfully updated");
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
        },

        deleteTable(id, index) {
            swal({
                title: "Are you sure?",
                text: "To delete this Table",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    axios.delete('table/' + id)
                        .then((response) => {
                            if (response.data[0] === 'success') {
                                this.tables.splice(index, 1);
                                toastr["success"](response.data[1]);
                            } else {
                                toastr["error"](response.data[1]);
                            }
                        })
                        .catch((error) => {
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
