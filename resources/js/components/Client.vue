<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#add-client" @click="clearClientForm()">
                        <strong>New Client</strong>
                    </button>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-row">
                    <div class="col">
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>List of Clients</strong>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in clientList">
                                    <th scope="row">{{ item.id }}</th>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.email }}</td>
                                    <td>
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            Option
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="action">
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit-client"
                                                @click="setClient(item.id)">Edit</a>
                                            <a class="dropdown-item red" href="#" @click="deleteProject(item.id)">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="add-client" tabindex="-1" role="dialog" aria-labelledby="add-tagTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add New Client</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createNewClient" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="form-group">
                                <input class="form-control" type="text" v-model="form.name" value="" placeholder="Enter client name"
                                    :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="email" v-model="form.email" value="" placeholder="Enter client email address"
                                    :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <textarea  class="form-control" v-model="form.bio" rows="8" cols="80" placeholder="Enter mini bio here"
                                    :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="loadClients()" ref="modalAddClientClose">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit-client" tabindex="-1" role="dialog" aria-labelledby="add-tagTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Client</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="updateClient" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="form-group">
                                <input class="form-control" type="text" v-model="form.name" value="" :placeholder="form.name"
                                :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="email" v-model="form.email" value="" :placeholder="form.email"
                                :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <textarea  class="form-control" v-model="form.bio" rows="8" cols="80" placeholder="Enter mini bio here"
                                :class="{ 'is-invalid': form.errors.has('bio') }">{{ form.bio }}</textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="loadClients()" ref="modalClientClose">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                clientList: '',
                form: new Form({
                    name:  '',
                    email: '',
                    type:  '',
                    bio:   ''
                }),
                id: '',
            }
        },
        created() {
            this.loadClients();
        },
        methods: {
            /**
             * Clear client form
             */
            clearClientForm: function() {
                form.name = '';
                form.email = '';
                form.bio = '';
            },

            /**
             * Set ID use for editing project
             * @param  {int} id
             * @return void
             */
            setClient: function(id) {
                this.id = id;

                axios.get('/projects/client/' + id).then(response => {
                    this.form = new Form(response.data);
                });
            },

            /**
             * Update project name
             */
            updateClient: function() {
                axios.post('/projects/client/' + this.id, {
                    _method: 'put',
                    name:  this.form.name,
                    email: this.form.email,
                    type:  this.form.type,
                    bio:   this.form.bio,
                }).then(response => {
                    const elem = this.$refs.modalClientClose;
                    elem.click();

                    toastr.info('Successfully updated client info');
                    this.loadClients();
                });
            },

            /**
             * Delete project own by the user
             */
            deleteProject: function(id) {
                axios.post('/projects/client/'+id, {
                    _method: 'delete'
                }).then(response => {
                    toastr.info('Successfully deleted client info');
                    this.loadClients();
                });
            },

            /**
             * Load projects owned by the user
             */
            loadClients: function() {
                window.axios.get('/projects/client').then(response => {
                    this.clientList = response.data;
                });
            },

            /**
             * Submit project information
             */
            createNewClient: function() {
                this.form.post('/projects/client').then(({ data }) => {
                    const elem = this.$refs.modalAddClientClose;
                    elem.click();

                    toastr.info('Successfully created a new client');
                    this.loadClients();
                });
            }
        },
    }
</script>
