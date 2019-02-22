<template>
    <div class="container-fluid">
        <div class="row">

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="no-projects" v-if="projects">

                    <div class="row">
                        <div class="col-sm-12">
                            <h2 class="float-left project-title">Projects</h2>
                            <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#projectCreate">New Project</button>
                        </div>
                    </div>

                    <hr>

                    <div v-if="projects.length > 0">
                        <div class="card" v-for="project in projects" :key="project.id">
                            <div class="card-header">
                                <strong style="text-transform: capitalize;">{{ project.name }}</strong>

                                <button class="btn btn-success btn-sm float-right" :disabled="counter.timer" data-toggle="modal" data-target="#timerCreate" @click="selectedProject = project">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <ul class="list-group list-group-flush" v-if="project.timers.length > 0">
                                <li v-for="timer in project.timers" :key="timer.id" class="list-group-item">
                                    <strong class="timer-name">{{ capitalizeFirstLetter(timer.name) }}</strong>
                                    <div class="">
                                        <span v-if="showTimerForProject(project, timer)" style="margin-right: 10px">
                                            <strong>{{ activeTimerString }}</strong>
                                        </span>
                                        <span v-else style="margin-right: 10px">
                                            <strong>{{ calculateTimeSpent(timer) }}</strong>
                                        </span>
                                        <button v-if="showTimerForProject(project, timer)" class="btn btn-sm btn-danger" @click="stopTimer()">
                                            <i class="fas fa-stop"></i>
                                        </button>
                                    </div>
                                </li>
                            </ul>

                            <p class="ml-4  mr-4" v-else>Nothing has been recorded for "{{ project.name }}". Click the play icon to record.</p>
                        </div>

                        <!-- Create Timer Modal -->
                        <div class="modal fade" id="timerCreate" tabindex="-1" role="dialog" aria-labelledby="timerCreate" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="timerCreateTitle">Record Time</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input v-model="newTimerName" type="text" class="form-control" id="usrname" placeholder="What are you working on?">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button data-dismiss="modal" v-bind:disabled="newTimerName === ''" @click="createTimer(selectedProject)" type="submit" class="btn btn-default btn-primary">
                                            <i class="fas fa-play"></i> Start
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else>
                        <h3 align="center">You need to create a new project</h3>
                    </div>

                    <div class="modal fade" id="projectCreate" tabindex="-1" role="dialog" aria-labelledby="projectCreate" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="projectCreateTitle">New Project</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input v-model="newProjectName" type="text" class="form-control" id="usrname" placeholder="Project Name">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" v-bind:disabled="newProjectName == ''" @click="createProject" type="submit" class="btn btn-default btn-primary">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="timers" v-else>
                    Loading...
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import moment from 'moment'

export default {
    data() {
        return {
            projects:          null,
            newTimerName:      '',
            newProjectName:    '',
            activeTimerString: 'Calculating...',
            counter: {
                seconds: 0,
                timer: null
            },
        }
    },
    created() {
        window.axios.get('/projects/timer').then(response => {
            console.log(response.data);

            this.projects = response.data

            window.axios.get('/project/timers/active').then(response => {
                if (response.data.id !== undefined) {
                    this.startTimer(response.data.project, response.data)
                }
            })
        })
    },
    methods: {
        /**
         * Conditionally pads a number with "0"
         */
        _padNumber: number =>  (number > 9 || number === 0) ? number : "0" + number,

        /**
         * Splits seconds into hours, minutes, and seconds.
         */
        _readableTimeFromSeconds: function(seconds) {
            const hours = 3600 > seconds ? 0 : parseInt(seconds / 3600, 10)
            return {
                hours: this._padNumber(hours),
                seconds: this._padNumber(seconds % 60),
                minutes: this._padNumber(parseInt(seconds / 60, 10) % 60),
            }
        },

        /**
         * Calculate the amount of time spent on the project using the timer object.
         */
        calculateTimeSpent: function (timer) {
            if (timer.stopped_at) {
                const started = moment(timer.started_at)
                const stopped = moment(timer.stopped_at)
                const time    = this._readableTimeFromSeconds(
                    parseInt(moment.duration(stopped.diff(started)).asSeconds())
                )
                return `${time.hours} Hours | ${time.minutes} mins | ${time.seconds} seconds`
            }
            return ''
        },

        /**
         * Determines if there is an active timer and whether it belongs to the project
         * passed into the function.
         */
        showTimerForProject: function (project, timer) {
            return this.counter.timer &&
                   this.counter.timer.id === timer.id &&
                   this.counter.timer.project.id === project.id
        },

        /**
         * Start counting the timer. Tick tock.
         */
        startTimer: function (project, timer) {
            const started = moment(timer.started_at)

            this.counter.timer         = timer
            this.counter.timer.project = project
            this.counter.seconds       = parseInt(moment.duration(moment().diff(started)).asSeconds())
            this.counter.ticker        = setInterval(() => {
                const time = this._readableTimeFromSeconds(this.counter.seconds)

                let activeTimer = `${time.hours} Hours | ${time.minutes}:${time.seconds}`;
                this.activeTimerString = this.capitalizeFirstLetter(activeTimer);
            }, 1000)
        },

        /**
         * Capitalize first letter
         */
        capitalizeFirstLetter: function(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },

        /**
         * Stop the timer from the API and then from the local counter.
         */
        stopTimer: function () {
            window.axios.post(`/projects/${this.counter.timer.id}/timers/stop`)
                        .then(response => {
                            // Loop through the projects and get the right project...
                            this.projects.forEach(project => {
                                if (project.id === parseInt(this.counter.timer.project.id)) {
                                    // Loop through the timers of the project and set the `stopped_at` time
                                    return project.timers.forEach(timer => {
                                        if (timer.id === parseInt(this.counter.timer.id)) {
                                            return timer.stopped_at = response.data.stopped_at
                                        }
                                    })
                                }
                            });

                            // Stop the ticker
                            clearInterval(this.counter.ticker)

                            // Reset the counter and timer string
                            this.counter = { seconds: 0, timer: null }
                            this.activeTimerString = 'Calculating...'
                        })
        },

        /**
         * Create a new timer.
         */
        createTimer: function (project) {
            window.axios.post(`/projects/${project.id}/timers`, {
                name: this.newTimerName
            })
            .then(response => {
                project.timers.push(response.data)
                this.startTimer(response.data.project, response.data)
            })

            this.newTimerName = ''
        },

        /**
         * Create a new project.
         */
        createProject: function () {
            window.axios.post('/projects', {name: this.newProjectName})
                        .then(response => this.projects.push(response.data))

            this.newProjectName = ''
        }
    },
}
</script>
