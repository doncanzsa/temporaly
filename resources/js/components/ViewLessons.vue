<template>
    <div>
        <h3 class="text-center mb-5">
            Listado de Clases
            <router-link to="/lessons/add" class="btn btn-primary ml-5">Agregar Nueva Clase</router-link>
        </h3>
        <div class="form-group row">
            <label for="subject_id" class="col-sm-3 col-form-label text-right">Seleccione Asignatura
                <div v-show="loadingSubjects" class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </label>
            <div class="col-sm-6">
                <select name="subject_id" class="form-control" v-model="subject_id">
                    <option v-for="subject in Subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
                </select>
            </div>
            <div class="col-sm-3">
                <div v-show="loadingDataTable">
                    <div class="spinner-grow text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-success" role="status">
                    <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-danger" role="status">
                    <span class="sr-only">Loading...</span>
                    </div>
                    <span>Obteniendo datos...</span>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col">
                <table id="informationTable" class="table display table-bordered nowrap" cellspacing="0" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Asignatura</th>
                            <th>Thema</th>
                            <th>Contenido</th>
                            <th>Updated</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <info-modal :DataRow="Row" v-on:closeInfoModal="clearVar()"></info-modal>
    </div>
</template>

<script>
// import DataTable from './DataTable.vue'
import InfoModal from './InfoModal';

    export default {
        components:{
            InfoModal
        },
        data() {
            return {
                subject_id  : null,
                Subjects    : [],
                loadingDataTable : false,
                loadingSubjects : false,
                loadData    : {},
                Lessons     : [],
                dataTable   : null,
                // ------------------------
                Row : null,
                my_filter: null,
            }
        },
        watch: {
            subject_id: function(val, oldVal) {
                let vm = this;
                if (vm.subject_id != null && vm.subject_id != '') {
                    vm.loadDataTable(vm.subject_id);
                }
            },
        },
        methods: {
            deleteItem(id) {
                let vm = this;
                if(confirm("¿Esta seguro de borrar las asignatura?")){
                    axios.delete('/api/lessons/delete/'+id)
                    .then(response => {
                        let data = response.data;
                        if (data.success) {
                            vm.$notify({
                                type: 'success',
                                group: 'foo',
                                title: 'Success',
                                text: 'Datos eliminados con exito!'
                            });
                            vm.loadDataTable(vm.subject_id);
                        } else {
                            vm.$notify({
                                type: 'error',
                                group: 'foo',
                                title: 'Error',
                                text: 'Ups algo salio mal...!'
                            });
                        }
                    })
                    .catch(error => {
                        vm.$notify({
                            type: 'error',
                            group: 'foo',
                            title: 'Error',
                            text: 'Ups algo salio mal...!'
                        });
                        console.log(error);
                    })
                }
            },
            editItem(id) {
                this.$router.push({ name: 'edit-lesson', params: { id }});
            },
            clearVar() {
                this.Row = null;
            },
            getSubjects() {
                let vm = this;
                vm.loadingSubjects = true;
                vm.axios
                .get('/api/subjects')
                .then(response => {
                    vm.Subjects = response.data;
                }).catch(err => {
                    console.log(err);
                }).finally(() => vm.loadingSubjects = false);
            },
            loadDataTable(id) {
                let vm = this;
                vm.loadingDataTable = true;
                let lessons_ = [];
                vm.axios
                    .get('/api/lessons/subject/'+id)
                    .then(response => {
                        lessons_ = response.data;
                        vm.Lessons = response.data;
                    }).then(() => {
                        vm.dataTable.clear().draw();
                        // lesson.content,
                        lessons_.forEach( (lesson, index) => {
                            vm.dataTable.row.add([
                                lesson.id,
                                lesson.subjects.name,
                                lesson.theme,
                                function () {
                                    let texto = lesson.content.replace(/<[^>]*>?/g, '');
                                    return `<a class='pointer btn_show' data-toggle='modal' data-idx='${index}' data-target='#InfoModal'>` +  texto.substr(0,100) + `...</a>`;
                                },
                                function () {
                                    return vm.moment(lesson.updated_at).format('LLL');
                                },
                                function () {
                                    let btn_show = `<span class="btn btn-sm btn-primary ml-2 btn_show" data-toggle='modal' data-idx='${index}' data-target='#InfoModal'>Mostrar</span>`
                                    let btn_edit = `<span class="btn btn-sm btn-success ml-2 btn_edit" data-id="${lesson.id}">Editar</span>`
                                    let btn_delete = `<span class="btn btn-sm btn-danger ml-2 btn_delete" data-id="${lesson.id}">Borrar</span>`
                                    return `${btn_show} ${btn_edit} ${btn_delete}`;
                                }
                            ]).draw(false);
                        });
                    }).finally(() => {
                        vm.loadingDataTable = false;
                    });
            }
        }, 
        created() {
            console.log("InfoModal created");
            // this.$router.push({ name: 'packages-id', params: { id: idP }});
        },
        mounted(){
            let vm = this;
            vm.dataTable = $('#informationTable').DataTable({
                buttons: [
                    'selectRows',
                    'selectColumns',
                    'selectCells'
                ],
                'languague' : {
                    'emptyTable' : "...",
                }
            });
            $("#informationTable_length").addClass("col-4");
            $("#informationTable_length").addClass("length_table");
            $("#informationTable_filter").addClass("col-8");
            $("#informationTable_filter").addClass("filter_table");
            $("#informationTable_filter").addClass("col-form-label");
            $("#informationTable_filter input[type='search']").addClass("form-control");
            $("#informationTable_filter input[type='search']").css("width","350");
            
            vm.getSubjects();
            $(vm.$el).on("click",".btn_show",function(){
                let index = $(this).attr('data-idx');
                vm.Row = vm.Lessons[index];
                vm.Row.filter = vm.my_filter;
            });
            
            $(vm.$el).on("click",".btn_edit",function(){
                let id = $(this).attr('data-id');
                vm.editItem(id);
            });

            $(vm.$el).on("click",".btn_delete",function(){
                let id = $(this).attr('data-id');
                console.log(id);
                vm.deleteItem(id);
            });

            const $dt = $('#informationTable').DataTable();
            $dt.on('search.dt', function(e) {
                vm.my_filter = $dt.search();
            });
        }
    }
</script>
<style>
    .bs {
        font-weight: 800;
        text-decoration: underline green;
        text-transform: uppercase;
    }

    .filter_table {
        display: inline-block;
        text-align: right;
    }

    .length_table{
        display: inline-block;
    }

    .pointer {
        cursor: pointer;
    }
</style>