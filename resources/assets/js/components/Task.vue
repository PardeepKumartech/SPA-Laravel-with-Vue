<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <H3><span class="glyphicon glyphicon-dashboard"></span> Assignment Dashboard </H3> <br>
                        <button @click="initAddTask()" class="btn btn-success " style="padding:5px">
                            Add New Assignment
                        </button>
                         
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="tasks.length > 0">
                            <tbody>
                            <tr class="table-primary">
                                <th>
                                    No.
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Description
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                            <tr v-for="(task, index) in tasks">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    {{ task.name }}
                                </td>
                                <td>
                                    {{ task.description }}
                                </td>
                                <td>
                                    <button @click="initUpdate(index)" class="btn btn-success btn-xs" style="padding:8px"><span class="glyphicon glyphicon-edit"></span></button>
                                  
                                    <button @click="deleteTask(index)" class="btn btn-danger btn-xs" style="padding:8px"><span class="glyphicon glyphicon-trash"></span></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="add_task_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add New Assignment
                        </button></h4>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="names">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Task Name" class="form-control"
                                   v-model="task.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control"
                                      placeholder="Task Description" v-model="task.description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createTask" class="btn btn-primary">Create</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" tabindex="-1" role="dialog" id="update_task_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Update Task</h4>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" placeholder="Assignment Title" class="form-control"
                                   v-model="update_task.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea cols="30" rows="5" class="form-control"
                                      placeholder="Assignment Description" v-model="update_task.description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateTask" class="btn btn-primary">Create</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>
</template>

<script>
    export default {
        data(){
            return {
                task: {
                    name: '',
                    description: ''
                },
                errors: [],
                tasks: [],
                update_task: {}
            }
        },
        mounted()
        {
            this.readTasks();
        },
        methods: {

            deleteTask(index)
            {
                let conf = confirm("Do you ready want to delete this task?");
                if (conf === true) {

                    axios.delete('/task/' + this.tasks[index].id)
                        .then(response => {

                            this.tasks.splice(index, 1);

                        })
                        .catch(error => {

                        });
                }
            },
            initAddTask()
            {
                $("#add_task_model").modal("show");
            },
            createTask()
            {
                axios.post('/task', {
                    name: this.task.name,
                    description: this.task.description,
                })
                    .then(response => {

                        this.reset();

                        this.tasks.push(response.data.task);

                        $("#add_task_model").modal("hide");

                    })
                    .catch(error => {
                        this.errors = [];

                        if (error.response.data.errors && error.response.data.errors.name) {
                       
                            this.errors.push(error.response.data.errors.name[0]);
                        }
 if (error.response.data.errors && error.response.data.errors.description)
                       {
                            this.errors.push(error.response.data.errors.description[0]);
                        }
                    });
            },
            reset()
            {
                this.task.name = '';
                this.task.description = '';
            },
            readTasks()
            {
                axios.get('http://phplaravel-144478-440242.cloudwaysapps.com/task')
                    .then(response => {

                        this.tasks = response.data.tasks;

                    });
            },
            initUpdate(index)
            {
                this.errors = [];
                $("#update_task_model").modal("show");
                this.update_task = this.tasks[index];
            },
            updateTask()
            {
                axios.patch('/task/' + this.update_task.id, {
                    name: this.update_task.name,
                    description: this.update_task.description,
                })
                    .then(response => {

                        $("#update_task_model").modal("hide");

                    })
                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.name) {
                            this.errors.push(error.response.data.errors.name[0]);
                        }

                        if (error.response.data.errors.description) {
                            this.errors.push(error.response.data.errors.description[0]);
                        }
                    });
            }
        }
    }
</script>