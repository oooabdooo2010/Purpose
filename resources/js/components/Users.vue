<template>
    <div>
        <br>
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data Table For Users</h3>
                        <button type="button" class="btn btn-primary" @click="createUserM()">
                            <i class="fa fa-user-plus"></i>
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="test" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>E-mail</th>
                                    <th>Type</th>
                                    <th>Bio</th>
                                    <th>created</th>
                                    <th>CTRL</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="user in users" :key="user.id">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.type | UpText}}</td>
                                    <td>{{ user.bio }}</td>
                                    <td>{{ user.created_at | UpDate}}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary"  @click="UpdateUserM(user)">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger" @click="deleteUser(user.id)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>E-mail</th>
                                <th>Type</th>
                                <th>CTRL</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
        <!-- /.box -->
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" v-show="typeMode">Add User Modal</h4>
                        <h4 class="modal-title" v-show="!typeMode">Update User Modal</h4>
                    </div>
                    <form @submit.prevent="typeMode ? createUserM() : UpdateUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Username</label>
                                <input v-model="form.name" type="text" name="name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name" class="text-danger"></has-error>
                            </div>

                            <div class="form-group">
                                <label>E-mail</label>
                                <input v-model="form.email" type="text" name="email"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email" class="text-danger"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Bio</label>
                                <textarea v-model="form.bio" name="bio"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }">
                                </textarea>
                                <has-error :form="form" field="bio" class="text-danger"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Type</label>
                                <select v-model="form.type" name="type"
                                          class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="">Select User</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                    <option value="author">Author</option>
                                </select>
                                <has-error :form="form" field="type" class="text-danger"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input v-model="form.password" type="password" name="password"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password" class="text-danger"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" v-show="typeMode" class="btn btn-primary">Create</button>
                            <button type="submit" v-show="!typeMode"  class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
</template>

<script>

    export default {
        data() {
            return {
                typeMode: false,
                users: {},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    bio: '',
                    type: '',
                    password: '',
                })
            }
        },
        methods: {
            UpdateUser(){
                this.$Progress.start();
                this.form.patch('api/user/'+ this.form.id)
                    .then(() => {

                        $('#modal-default').modal('hide');
                        Fire.$emit('AfterCreated');
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: 'Your work has been Updated',
                            showConfirmButton: false,
                            timer: 3000
                        })
                        this.$Progress.finish();

                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            },
            UpdateUserM(user){
                this.typeMode = false;
                this.form.reset();
                $('#modal-default').modal('show');
                this.form.fill(user);
            },
            createUserM(){
                this.typeMode = true;
                this.form.reset();
                $('#modal-default').modal('show');
            },
            showUser(){
                //$('#test').DataTable();
                axios.get('api/user').then(({ data }) => (this.users = data.data));
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
                    .then(() => {
                        $('#modal-default').modal('hide');
                        Fire.$emit('AfterCreated');
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: 'Your work has been saved',
                            showConfirmButton: false,
                            timer: 3000
                        })
                        this.$Progress.finish();
                    })
                    .catch(() => {

                    })

            },
            deleteUser(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        this.form.delete('api/user/'+id)
                            .then(() => {
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                );
                                Fire.$emit('AfterCreated');
                            })
                            .catch(() => {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!',
                                    footer: '<a href>Why do I have this issue?</a>'
                                })
                            })


                    }
                })

            }

        },
        created() {
            this.showUser();
            Fire.$on('AfterCreated', () => {
                this.showUser();
            })
            //setInterval(() => this.showUser(), 3000);
        }
        //name: "Users"
    }
</script>


<style scoped>

</style>
