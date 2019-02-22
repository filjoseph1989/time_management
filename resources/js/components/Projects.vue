<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#add-project">New Project</button>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-sm-12">
                <form @submit.prevent="filterProject">
                    <div class="form-row">
                        <div class="col">
                            <label for="">Status</label>
                            <select class="form-control" v-model="formFilter.status">
                                <option value="active" selected="true">Active</option>
                                <option value="archived">Archived</option>
                                <option value="both">Both</option>
                            </select>
                        </div>

                        <div class="col">
                            <label for="">Team</label>
                            <select class="form-control" v-model="formFilter.team">
                                <option v-for="(item, key) in teams" v-bind:selected="key === 0" :value="item.id">{{ item.name | changeToCapitalize }}</option>
                            </select>
                        </div>

                        <div class="col">
                            <label for="">Search by name</label>
                            <input class="form-control" type="text" v-model="formFilter.keyword" placeholder="Search">
                        </div>

                        <div class="col">
                            <label for=""></label>
                            <button class="btn btn-success" type="submit" style="margin-top: 30px;">Apply</button>
                            <button class="btn btn-primary" type="submit" style="margin-top: 30px; display: inline" @click="loadProjects()">Clear</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>List of Projects</strong>
                    </div>

                    <div class="card-body" v-if="projectList.data != undefined && projectList.data.length > 0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Team</th>
                                    <th scope="col">Client</th>
                                    <th scope="col">Created</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in projectList.data">
                                    <th scope="row">{{ item.id }}</th>
                                    <td>
                                        <router-link :to="'/projects-page/' + item.id">{{ item.name | changeToCapitalize }}</router-link>
                                    </td>

                                    <td v-if="item.team != null">{{ item.team.name | changeToCapitalize }}</td>
                                    <td v-else></td>

                                    <td v-if="item.user != null">{{ item.user.name | changeToCapitalize }}</td>
                                    <td v-else></td>

                                    <td>{{ item.created_at | formateDate }}</td>
                                    <td>
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Option
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="action">
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit-project"
                                                @click="setProject(item.id)">Edit</a>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#add-client"
                                                @click="getProjectClients(item.id)">Add Client</a>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#add-team"
                                                @click="setProjectId(item.id)">Add|Update Team</a>
                                            <a class="dropdown-item red" href="#" @click="deleteProject(item.id)">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body" v-else>
                        <p>No Project Found</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for adding project -->
        <div class="modal fade" id="add-project" tabindex="-1" role="dialog" aria-labelledby="add-tagTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Adding New Project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createNewProject" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="form-group">
                                <input class="form-control" type="text" v-model="form.name" value="" placeholder="Enter project name"
                                    :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="loadProjects()" ref="modalProjectClose">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal for editing project -->
        <div class="modal fade" id="edit-project" tabindex="-1" role="dialog" aria-labelledby="add-tagTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input class="form-control" type="text" v-model="form.name" placeholder="Enter project name"
                                :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="loadProjects()" ref="modalProjectEditClose">Close</button>
                        <button type="submit" class="btn btn-primary" @click="updateProject()">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add client modal -->
        <div class="modal fade" id="add-client" tabindex="-1" role="dialog" aria-labelledby="add-tagTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add client</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="addProjectClient" @keydown="formclient.onKeydown($event)">
                        <div class="modal-body">
                            <input type="hidden" v-model="formclient.project">
                            <input type="hidden" v-model="formclient._method">
                            <div class="form-group">
                                <select class="form-control" v-model="formclient.client">
                                    <option :value="item.id" v-for="item in clients">{{ item.name | changeToCapitalize }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="loadProjects()" ref="modalProjectAddClientClose">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add team -->
        <div class="modal fade" id="add-team" tabindex="-1" role="dialog" aria-labelledby="add-tagTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add|Update Team</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="addProjectTeam" @keydown="formTeam.onKeydown($event)">
                        <div class="modal-body">
                            <input type="hidden" v-model="formTeam._method">
                            <input type="hidden" v-model="formTeam.project">

                            <div class="form-group">
                                <select class="form-control" v-model="formTeam.team">
                                    <option :value="item.id" v-for="item in teams">{{ item.name | changeToCapitalize }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="loadProjects()" ref="modalProjectAddTeamClose">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
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
                projectList: {},
                form: new Form({
                    name: ''
                }),
                formFilter: new Form({
                    status:  '',
                    team:    '',
                    keyword: ''
                }),
                formclient: new Form({
                    client: '',
                    project: '',
                    _method: 'put'
                }),
                formTeam: new Form({
                    team: '',
                    project: '',
                    _method: 'put'
                }),
                teams: {},
                clients: {},
                id: '',
                new: '',
            }
        },
        created() {
            this.loadProjects();

            // Get the list of team
            window.axios.get('/projects/get/team').then(response => {
                this.teams = response.data;
            });
        },
        methods: {
            /**
             * Set project ID use for update
             * @param  {int} id
             */
            setProjectId: function(id) {
                this.formTeam.project = id;
            },

            /**
             * Add team to a project
             */
            addProjectTeam: function() {
                // modalProjectAddTeamClose
                this.formTeam.post(' projects/projectteam/' + this.formTeam.project).then((response) => {
                    const elem = this.$refs.modalProjectAddTeamClose;
                    elem.click();

                    toastr.info('Successfully added team');
                    this.loadProjects();
                });
            },

            /**
             * Return the list of client for dropdown
             */
            getProjectClients: function(id) {
                this.formclient.project = id;

                axios.get('/projects/get/project/clients').then(response => {
                    this.clients = response.data;
                });
            },

            /**
             * Add the project client
             */
            addProjectClient: function() {
                this.formclient.post('/projects/projectclient/' + this.formclient.project).then((response) => {
                    const elem = this.$refs.modalProjectAddClientClose;
                    elem.click();

                    toastr.info('Successfully added client');
                    this.loadProjects();
                });
            },

            /**
             * Load filtered projects result
             */
            filterProject: function() {
                this.formFilter.post('/projects/project/filter').then((response) => {
                    this.projectList = response.data;
                });
            },

            /**
             * Set ID use for editing project
             * @param  {int} id
             * @return void
             */
            setProject: function(id) {
                this.id = id;

                axios.get('/projects/project/' + id).then(response => {
                    this.form = new Form(response.data);
                });
            },

            /**
             * Update project name
             */
            updateProject: function() {
                axios.post('/projects/project/'+ this.id, {
                    _method: 'put',
                    name: this.form.name
                }).then(response => {
                    const elem = this.$refs.modalProjectEditClose;
                    elem.click();

                    toastr.info('Successfully updated client info');
                    this.loadProjects();
                });
            },

            /**
             * Delete project own by the user
             */
            deleteProject: function(id) {
                axios.post('/projects/project/'+id, {
                    _method: 'delete'
                }).then(response => {
                    toastr.info('Successfully deleted project');
                    this.loadProjects();
                });
            },

            /**
             * Load projects owned by the user
             */
            loadProjects: function() {
                window.axios.get('/projects/project').then(response => {
                    this.projectList = response.data;
                });
            },

            /**
             * Submit project information
             */
            createNewProject: function() {
                this.form.post('/projects/project').then(({ data }) => {
                    const elem = this.$refs.modalProjectClose;
                    elem.click();

                    toastr.info('Successfully created new project');
                    this.loadProjects();
                });
            }
        },
    }
</script>
