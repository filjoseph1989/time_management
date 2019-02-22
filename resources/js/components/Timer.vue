<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <input v-model="newTimerName" type="text" class="form-control" id="usrname" placeholder="What are you working on?">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button v-if="isRunning() == false" v-bind:disabled="newTimerName === ''" @click="startTimer()" type="submit" class="btn btn-default btn-primary">
                                        <i class="fas fa-play"></i> Start
                                    </button>
                                    <button v-else type="button" class="btn btn-default btn-danger" @click="stopTimer()">
                                        <i class="fas fa-stop"></i> Stop
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Today</strong>
                    </div>

                    <div class="card-body" v-if="isRunning() == false && isListItem() == false">
                        <p>Enter what your doing above and Click start</p>
                    </div>

                    <div class="card-body" v-if="listOfTodayTimers.length != 0">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item" v-if="isListItem() == true">
                                        <span class="badge badge-secondary" @click="showOrHideChildTimer()">{{ todayCount }}</span>

                                        <input class="timer-description" type="text" v-model="newTimerDescription" name="name" value="" placeholder="Enter description here">

                                        <span class="float-right">
                                            <i class="fa-options fas fa-tags" title="Add tag" data-toggle="modal" data-target="#add-tag"></i>
                                            <i class="mr-4 fa-options fas fa-dollar-sign" title="Add Cost"></i>

                                            {{ hr }}:{{ min }}:{{ sec }}

                                            <i v-if="isRunning() == true" class="ml-2 fas fa-stop red" @click="stopTimer()" title="Sop Timer"></i>
                                            <i v-if="isRunning() == false" class="ml-2 fas fa-play green" @click="startTimer(1)" title="Continue Timer"></i>

                                            <!-- Task 1 -->
                                            <!-- <i class="green fas fa-plus" title="Add New Timer"></i> -->
                                        </span>
                                    </li>
                                </ul>

                                <ul v-if="listOfTodayTimers.length != 0 && childTimer == 1" class="list-group list-group-flush">
                                    <li class="list-group-item" v-for="timer in listOfTodayTimers">
                                        {{ timer.description | checkDescription }}

                                        <div class="float-right">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item red" href="#" @click="deleteTimer(timer.id)">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="mr-2 float-right">
                                            {{ timer.interval | splitDate }}
                                        </span>
                                        <span class="float-right">
                                            <i class="fa-options fas fa-tags" title="Add tag"></i>
                                            <i class="mr-4 fa-options fas fa-dollar-sign" title="Add Cost"></i>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Today</strong>
                    </div>
                    <div class="card-body">
                        <ul v-if="listOfTodayTimers.length != 0" class="list-group list-group-flush">
                            <li class="list-group-item" v-for="timer in listOfTodayTimers">
                                {{ timer.description | checkDescription }}

                                <div class="float-right">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item red" href="#" @click="deleteTimer(timer.id)">Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="mr-2 float-right">
                                    {{ timer.interval | splitDate }}
                                </span>
                                <span class="float-right">
                                    <i class="fa-options fas fa-tags" title="Add tag"></i>
                                    <i class="mr-4 fa-options fas fa-dollar-sign" title="Add Cost"></i>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card" v-for="(timerDay, index) in listOfTimers">
                    <div class="card-header">
                        <strong>{{ index }}</strong>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" v-for="item in timerDay">
                                {{ item.name | changeToCapitalize }}
                                -
                                {{ item.description | checkDescription }}

                                <div class="float-right">
                                    <div class="float-right">
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item red" href="#" @click="deleteTimer(item.id)">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="mr-2 float-right">
                                    {{ item.interval | splitDate }}
                                </span>
                                <span class="float-right">
                                    <i class="fa-options fas fa-tags" title="Add tag"></i>
                                    <i class="mr-4 fa-options fas fa-dollar-sign" title="Add Cost"></i>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal -->
        <div class="modal fade" id="add-tag" tabindex="-1" role="dialog" aria-labelledby="add-tagTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input class="form-control" type="text" name="tag" v-model="newTimerTag" value="" placeholder="Enter timer tag here">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Add</button>
                    </div>
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
            newTimerTag:         '',
            newTimerName:        '',
            newTimerDescription: '',
            timeStarted:         {},
            listOfTodayTimers:   {},
            listOfTimers:        {},
            second:     0,
            minute:     0,
            hour:       0,
            interval:   0,
            running:    0,
            listItem:   0,
            childTimer: 0,
            todayCount: 0,
        }
    },
    created() {
        axios.get('/projects/timer').then(response => {
            this.listOfTimers = response.data;
        })
    },
    computed: {
        sec: function() {
            if (this.second < 10) {
                return '0' + this.second;
            }

            return this.second;
        },
        min: function() {
            if (this.minute < 10) {
                return '0' + this.minute;
            }

            return this.minute;
        },
        hr: function() {
            if (this.hour < 10) {
                return '0' + this.hour;
            }

            return this.hour;
        },
    },
    methods: {
        /**
         * Delete timer in the database
         */
        deleteTimer: function(id) {
            axios.post('/projects/projecttimer/'+id, {
                _method: 'delete'
            }).then(response => {
                axios.get('/projects/timer').then(response => {
                    this.listOfTimers = response.data;
                })
                axios.get('/projects/timer/gettoday').then(response => {
                    this.listOfTodayTimers = response.data;
                    this.todayCount = this.listOfTodayTimers.length;
                })
            });
        },

        /**
         * Display or hide timer of today
         *
         * @return void
         */
        showOrHideChildTimer: function() {
            this.childTimer = this.childTimer == 1 ? 0 : 1;
        },

        /**
         * Create new item
         */
        newItem: function() {
            this.listItem++;

            // save to database current time counted
            // reset timer
        },

        /**
         * Start timer
         */
        startTimer: function() {
            this.listItem = 1;
            this.running  = 1;

            this.timeStarted = {
                hourStarted:   this.hr,
                minuteStarted: this.min,
                secondStarted: this.sec,
            };

            this._tick();
            this.interval = setInterval(this._tick, 1000);

            axios.get('/projects/timer/gettoday').then(response => {
                this.listOfTodayTimers = response.data;
                this.todayCount = this.listOfTodayTimers.length;
            })
        },

        /**
         * Stop the timer
         */
        stopTimer: function() {
            clearInterval(this.interval);

            let time = {
                hourStopped:   this.hr,
                minuteStopped: this.min,
                secondStopped: this.sec,
                hourStarted:   this.timeStarted.hourStarted,
                minuteStarted: this.timeStarted.minuteStarted,
                secondStarted: this.timeStarted.secondStarted,
                name:          this.newTimerName,
                description:   this.newTimerDescription,
                newTimerTag:   this.newTimerTag,
            };

            this.running = 0;

            axios.post('/projects/projecttimer', time).then(response => {
                axios.get('/projects/timer/gettoday').then(response => {
                    this.listOfTodayTimers = response.data;
                })
            });
        },

        /**
         * Check if there's timer running
         */
        isRunning: function() {
            if (this.running == 1) {
                return true;
            }

            return false;
        },

        /**
         * Check if its a list item
         */
        isListItem: function() {
            if (this.listItem > 0) {
                return true;
            }

            return false;
        },

        /**
         * Ticking time
         */
        _tick: function() {
            if (this.second !== 59) {
                this.second++;
                return;
            }

            if (this.second == 59) {
                this.second = 0;
            }

            if (this.minute !== 59) {
                this.minute++;
                return;
            }

            if (this.minute == 59) {
                this.minute = 0;
            }

            if (this.hour != 23) {
                this.hour++;
                return;
            }

            if (this.hour == 23) {
                this.hour == 0;
                return;
            }
        }
    },
}
</script>
