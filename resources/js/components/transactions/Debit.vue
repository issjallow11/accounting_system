<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <div class="card" v-if="$gate.isAdmin()">
              <div class="card-header">
                <h3 class="card-title">Debit List</h3>

                <div class="card-tools">

                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Add New
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Date</th>
                      <th>Reciept No</th>
                      <th>Customer Name</th>
                      <th>Payment</th>
                      <th>Payment Date</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="debit in debits.data" :key="debit.id">

                      <td>{{debit.id}}</td>
                      <td class="text-capitalize">{{debit.date}}</td>
                      <td class="text-capitalize">{{debit.receipt_no}}</td>
                      <td>{{debit.customer_name}}</td>
                      <td>{{debit.payment}}</td>
                      <td>{{debit.payment_date}}</td>
                      <!-- <td :inner-html.prop="user.email_verified_at | yesno"></td> -->
                      <td>{{debit.created_at}}</td>

                      <td>

                        <a href="#" @click="editModal(credit)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteCredit(debit.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="debits" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>


        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Debit the Cash book Account</h5>
                    <h5 class="modal-title" v-show="editmode">Update User's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form @submit.prevent="editmode ? updateUser() : debit()">
                    <div class="modal-body">
                        <div class="form-group">

                            <label>Account Type</label>
                            <select class="form-control" v-model="form.account_type">
                              <option
                                  v-for="(cat,index) in subAccounts.data" :key="index"
                                  :value="index"
                                  :selected="index == form.account_type">{{ cat.name }}</option>
                            </select>
                            <has-error :form="form" field="account_type"></has-error>
                        </div>
                        <div class="form-group">
                            <label>date</label>
                            <input v-model="form.date" type="date" name="date"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('date') }">
                            <has-error :form="form" field="date"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Receipt No:</label>
                            <input v-model="form.receipt_no" type="text" name="receipt_no"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('receipt_no') }">
                            <has-error :form="form" field="receipt_no"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Customer Name</label>
                            <input v-model="form.customer_name" type="customer_name" name="customer_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('customer_name') }">
                            <has-error :form="form" field="customer_name"></has-error>
                        </div>
                       <div class="form-group">
                            <label>Payment</label>
                            <input v-model="form.payment" type="number" name="payment"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('payment') }">
                            <has-error :form="form" field="payment"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Payment Date</label>
                            <input v-model="form.payment_date" type="date" name="payment_date"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('payment_date') }">
                            <has-error :form="form" field="payment_date"></has-error>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Credit Cash Book</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                editmode: false,
                debits : {},
                subAccounts:[],
                form: new Form({
                    id : '',
                    account_type:'',
                    date : '',
                    receipt_no: '',
                    customer_name: '',
                    payment: '',
                    payment_date: '',
                })
            }
        },
        methods: {

            getResults(page = 1) {

                  this.$Progress.start();

                  axios.get('api/debit?page=' + page).then(({ data }) => (this.debits = data.data));

                  this.$Progress.finish();
            },
            updateUser(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/debit/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                    this.loadDebits();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(user){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteDebit(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.delete('api/Debit/'+id).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadDebits();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },
          loadDebits(){
            this.$Progress.start();

            if(this.$gate.isAdmin()){
              axios.get("api/debit").then(({ data }) => (this.debits = data.data));
            }


            this.$Progress.finish();
          },
            loadSubAccounts(){
                axios.get("api/subAccountDebits").then(({ data }) => (this.subAccounts = data.data));

            },
        //   loadAccounts(){
        //       axios.get("/api/subAccount").then(({ data }) => (this.accounts = data));
        //   },

          debit(){

              this.form.post('api/debit')
              .then((response)=>{
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: response.data.message
                  });

                  this.$Progress.finish();
                  this.loadDebits();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
          }

        },
        mounted() {
            console.log('User Component mounted.')
        },
        created() {

            this.$Progress.start();
            this.loadDebits();
            this.loadSubAccounts();
            this.$Progress.finish();
        }
    }
</script>
