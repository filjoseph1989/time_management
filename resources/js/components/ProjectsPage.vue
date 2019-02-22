<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-success float-right" type="button">Done</button>
            </div>
        </div>

        <br>

        <div class="row" v-if="project.team == null">
            <div class="col-md-12">
                <form @submit.prevent="addProjectTeam" @keydown="formTeam.onKeydown($event)">
                    <div class="form-row">
                        <div class="col">
                            <input class="form-control" type="text" v-model="formTeam.name" placeholder="Add Team">
                        </div>

                        <div class="col">
                            <button class="btn btn-success" type="submit">Apply</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <br v-if="project.team == null">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong v-if="project.team != null">{{ project.team.name | changeToCapitalize }}</strong>
                        <strong v-else>No Team Yet</strong>

                        <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#add-team-member" @click="setTeamId()">Add Member</button>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in teamMembers">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.user.name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="add-team-member" tabindex="-1" role="dialog" aria-labelledby="add-tagTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Adding New Team Member</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="createNewTeamMember" @keydown="formTeamGroup.onKeydown($event)">
                        <div class="modal-body">
                            <div class="form-group">
                                <select class="form-control" v-model="formTeamGroup.user_id">
                                    <option :value="user.id" v-for="user in users">{{ user.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" ref="modalAddTeamMemberClose">Close</button>
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
                project: {},
                formTeam: new Form({
                    name: '',
                    project: '',
                    _method: 'put'
                }),
                formTeamGroup: new Form({
                    team_id: '',
                    user_id: '',
                }),
                users: {},
                teamMembers: {},
            }
        },
        created() {
            this.loadProject(this.$route.params.id);

            window.axios.get('/projects/user').then(response => {
                this.users = response.data;
            });
        },
        methods: {
            /**
             * Set team ID
             */
            setTeamId: function() {
                this.formTeamGroup.team_id = this.project.id;
            },

            /**
             * Create new team member
             */
            createNewTeamMember: function() {
                this.formTeamGroup.post('/projects/teamgroup').then((response) => {
                    this.getTeamMembers(this.formTeamGroup.team_id);
                });
            },

            /**
             * Add team to a project
             */
            addProjectTeam: function() {
                this.formTeam.post('/projects/projectteam/' + this.formTeam.project).then((response) => {
                    toastr.info('Successfully added team');
                    this.loadProject(response.data.project_id);
                });
            },

            /**
             * Load project
             */
            loadProject: function(id) {
                window.axios.get('/projects/projectteam/' + id ).then(response => {
                    this.project = response.data;
                    this.getTeamMembers(response.data.team.id);
                });
            },

            /**
             * Return team members
             *
             * @param  {int} id
             */
            getTeamMembers: function(id) {
                window.axios.get('/projects/teamgroup/get/team/members/' + id).then(response => {
                    this.teamMembers = response.data;
                });
            },
        },
    }
</script>
