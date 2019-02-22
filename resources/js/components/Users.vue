<template>
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header no-border">
                    <h3 class="card-title">Users</h3>
                    <div class="card-tools">
                        <button type="button" name="button" class="btn btn-success" data-toggle="modal" data-target="#add-new">
                            <i class="fas fa-user-plus fa-fw"></i> Add New
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Registered At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id">
                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.type | changeToUpperCase }}</td>
                                <td>{{ user.created_at | formateDate }}</td>
                                <td>
                                    <a href="#">
                                        <i class="fas fa-pencil-alt blue"></i>
                                    </a>
                                    <a href="#">
                                        <i class="fas fa-trash-alt red"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="add-new" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createUser">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }"
                                placeholder="Enter your name">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }"
                                placeholder="Enter your email address">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }"
                                placeholder="Enter your password">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                            <div class="form-group">
                                <textarea v-model="form.bio" name="bio" rows="8" cols="80"  class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"
                                placeholder="Enter mini bio"></textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>
                            <div class="form-group">
                                <select v-model="form.type" type="text" name="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="">Select User Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                    <option value="author">Author</option>
                                </select>
                                <has-error :form="form" field="type"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
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
                users: {},
                form: new Form({
                    name:     '',
                    email:    '',
                    password: '',
                    type:     '',
                    bio:      '',
                    photo:    ''
                })
            }
        },
        methods: {
            createUser() {
                this.form.post('api/user');
            },

            loadUsers() {
                axios.get('/api/user').then((response) => {
                    this.users = response.data.data;
                });
            }
        },
        created() {
            this.loadUsers();
        }
    }
</script>
