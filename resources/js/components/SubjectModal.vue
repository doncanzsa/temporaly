<template>
    <div class="modal fade" id="SubjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    <span v-if="Subject && Subject.id">Editar Asignatura</span>
                    <span v-else>Nueva Asignatura</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="SubjectForm">
                     <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" name="name" v-model="SubjectsForm.name" placeholder="Ingrese nombre">
                        <small class="form-text text-danger">{{SubjectsForm.name_msj}}</small>
                    </div>
                    <div class="form-group">
                        <label for="name">Descripcion</label>
                        <textarea name="description" class="form-control" rows="5" style="height:70px" 
                        v-model="SubjectsForm.description" placeholder="Ingrese descripcion"></textarea>
                        <small class="form-text text-danger">{{SubjectsForm.description_msj}}</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" :disabled="(onSubmit) ? 'disabled' : null" @click="saveItem()">
                    <span v-show="onSubmit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span v-if="Subject && Subject.id">Guardar Cambios</span>
                    <span v-else>Guardar</span>
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        Subject: {
            type : [Object, null],
            default: null,
        },
    },
    data() {
        return {
            closeModal   : false,
            onSubmit     : false,
            SubjectsForm : {
                id              : null,
                name            : null,
                description     : null,
            },
        }
    },
    watch: {
        Subject: function(val, oldVal) {
            let vm = this;
            if (_.isEmpty(vm.Subject) || vm.Subject.id == '') {
                vm.clearData();
            } else {
                vm.SubjectsForm.id = vm.Subject.id;
                vm.SubjectsForm.name = vm.Subject.name;
                vm.SubjectsForm.description = vm.Subject.description;
            }
        }, 
    },
    methods: {
        saveItem() {
            let vm = this;
            vm.onSubmit = true;
            axios.post('/api/subjects/save', 
                vm.SubjectsForm, // the data to post
            ).then(response => {
                let data = response.data;
                if(data.success) {
                    let obj = data.success;
                    vm.$emit('reloadSubject', obj);
                    $('#SubjectModal').modal('hide');
                    vm.onSubmit = false;
                    vm.$notify({
                        type: 'info',
                        group: 'foo',
                        title: 'Success',
                        text: 'Datos almacenados con exito!'
                    });
                } else {
                    vm.$notify({
                        type: 'error',
                        group: 'foo',
                        title: 'Error',
                        text: 'Ups algo salio mal...!'
                    });
                    vm.onSubmit = false;
                }
            }).catch(err => {
                vm.$notify({
                        type: 'error',
                        group: 'foo',
                        title: 'Error',
                        text: 'Ups algo salio mal...!'
                    });
                vm.onSubmit = false;
            })
        },
        clearData() {
            let vm = this;
            vm.SubjectsForm.id = null;
            vm.SubjectsForm.name = null;
            vm.SubjectsForm.description = null;
        }
    },
    created() {
        
    },
    mounted() {
        let vm = this;
        $('#SubjectModal').off('hidden.bs.modal');
        $('#SubjectModal').on('hidden.bs.modal', function (e) {
            vm.clearData();
            vm.$emit('closeSubjectModal');
        });
    }
}
</script>
<style>
</style>