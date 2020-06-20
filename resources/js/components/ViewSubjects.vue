<template>
    <div>
        <h3 class="text-center">Vista de Asignaturas</h3><br/>
        
        <div class="row">
            <div class="col-md-6">
                <label>Resultados: <b>{{Subjects.length}}</b></label>
            </div>
            <div class="col-md-6 text-right mb-3">
                <button class="btn btn-primary"
                data-toggle="modal" data-target="#SubjectModal">Agregar Nuevo</button>
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>DESCRIPCION</th>
                <th>CREACION</th>
                <th>MODIFICACION</th>
                <th class="text-center">OPCIONES</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="loadData"><td class="text-center font-weight-bold" colspan="6">Cargando Datos... </td></tr>
            <tr v-if="!loadData && Subjects.length==0"><td class="text-center font-weight-bold" colspan="6">No hay datos...</td></tr>
            <tr v-for="(subject, index) in Subjects" :key="subject.id">
                <td>{{ subject.id }}</td>
                <td>{{ subject.name }}</td>
                <td>{{ subject.description }}</td>
                <td>{{ moment(subject.created_at).format('LLL') }}</td>
                <td>{{ moment(subject.updated_at).format('LLL') }}</td>
                <td class="text-center">
                    <button class="btn btn-success" data-toggle="modal" data-target="#SubjectModal" @click="editItem(index)">Editar</button>
                    <button class="btn btn-danger" @click="deleteItem(subject.id, index)">Borrar</button>
                </td>
            </tr>
            </tbody>
        </table>
        <subject-modal :Subject="Subject"
        @reloadSubject="reloadSubject"
        @closeSubjectModal="closeSubjectModal()"
        ></subject-modal>
    </div>
</template>

<script>
    // import moment from 'moment'
    import SubjectModal from './SubjectModal';
    
    export default {
        components: {
            SubjectModal
        },
        data() {
            return {
                loadData    : false,
                Subject     : null,
                Subjects    : [],
            }
        },
        methods: {
            reloadSubject(obj) {
                this.getSubjects();
            },
            closeSubjectModal() {
                this.Subject = null;
            },
            getSubjects() {
                let vm = this;
                vm.loadData = true;
                vm.axios
                .get('/api/subjects')
                .then(response => {
                    vm.Subjects = response.data;
                    vm.loadData = false;
                }).catch(err => {
                    vm.loadData = false;
                    console.log(err);
                });
            },
            editItem(index) {
                this.Subject = this.Subjects[index];
            },
            deleteItem(id, index) {
                let vm = this;
                if(confirm("¿Esta seguro de borrar las asignatura?")){
                    axios.delete('/api/subjects/delete/'+id)
                    .then(response => {
                        let data = response.data;
                        if (data.success) {
                            vm.$notify({
                                type: 'success',
                                group: 'foo',
                                title: 'Success',
                                text: 'Datos eliminados con exito!'
                            });
                            this.Subjects.splice(index, 1);
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
        },
        mounted() {
            this.getSubjects();
        }
    }
</script>