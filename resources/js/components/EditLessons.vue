<template>
    <div> 
        <h3 class="text-center mb-5">
            Editar Clase 
            <div v-show="loadForm" class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </h3>
        <div class="row">
            <div class="col-md-12">
                <form>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-4">
                                <div class="form-group row">
                                    <label for="subject_id" class="col-sm-3 col-form-label text-right">Asignatura
                                        <div v-show="loadData.subjects" class="spinner-border spinner-border-sm" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </label>
                                    <div class="col-sm-9">
                                        <select name="subject_id" class="form-control" v-model="LessonForm.subject_id">
                                            <option v-for="subject in Subjects"
                                             :key="subject.id" :value="subject.id">{{ subject.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group row">
                                    <label for="theme" class="col-sm-3 col-form-label text-right">Tema
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" name="theme"  v-model="LessonForm.theme" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-right">
                                <button class="btn btn-primary" :disabled="saving" @click.prevent="saveItem()">
                                    <span v-if="saving">
                                        <span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
                                        Guardando...
                                    </span>
                                    <span v-else>Guardar Cambios</span>
                                </button>
                                <button class="btn btn-secondary" @click.prevent="cleanAll()">Limpiar</button>
                                <router-link to="/" class="btn btn-light">Cancelar</router-link>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12">
                                <label for="theme" class="col-sm-12 col-form-label text-center">Contenido</label>
                                <!-- <quill v-model="content" :config="config"></quill> -->
                                <quill-editor
                                    :content="content"
                                    :options="editorOption"
                                    @change="onEditorChange($event)"
                                />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loadForm : false,
                onSubmit    : false,
                LessonForm: {},
                loadData : [],
                Subjects : [],
                content: `<h2>Text Example</h2>`,
                editorOption: {
                // Some Quill options...
                },
                saving: false,
            }
        },
        methods: {
            cleanAll() {
                this.LessonForm = {};
                this.content = '';
            },
            saveItem() {
                let vm = this;
                let form = {};
                form = vm.LessonForm;
                form.content = vm.content;
                // console.log(form);
                vm.saveData(form);
            },
            saveData(dataSend) {
                let vm = this;
                vm.saving = true;
                this.axios
                    .post('/api/lessons/save', dataSend)
                    .then(response => {
                        // this.$router.push({name: 'home'})
                        let data = response.data;
                        if(data.success) {
                            let obj = data.success;
                            vm.$notify({
                                type: 'info',
                                group: 'foo',
                                title: 'Success',
                                text: 'Datos almacenados con exito!'
                            });
                            this.content = '';
                            vm.$router.push({name: 'home'});
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
                        console.log(error);
                        this.$notify({
                            type: 'error',
                            group: 'foo',
                            title: 'Error',
                            text: 'Ups algo salio mal...!'
                        });
                    })
                    .finally(() => {
                        this.onSubmit = false;
                        this.saving = false;
                    });
            },
            async getSubjects() {
                let vm = this;
                vm.loadData['subjects'] = true;
                vm.axios
                .get('/api/subjects')
                .then(response => {
                    vm.Subjects = response.data;
                    vm.loadData['subjects'] = false;
                }).catch(err => {
                    vm.loadData['subjects'] = false;
                    console.log(err);
                });
            },
            onEditorBlur(quill) {
                // console.log('editor blur!', quill)
            },
            onEditorFocus(quill) {
                // console.log('editor focus!', quill)
            },
            onEditorReady(quill) {
                // console.log('editor ready!', quill)
            },
            onEditorChange({ quill, html, text }) {
                // console.log('editor change!', quill, html, text)
                this.content = html
            }
        },
        computed: {
            editor() {
                return this.$refs.myQuillEditor.quill
            }
        },
        async created() {
            let vm = this;
            console.log('el id', vm.$route.params.id);
            await vm.getSubjects();
            vm.loadForm = true;
            vm.axios
            .get(`/api/lessons/show/${vm.$route.params.id}`)
            .then((response) => {
                let data = response.data;
                if(data.success) {
                    let obj = data.success;
                    console.log(obj);
                    vm.LessonForm.theme = obj.theme;
                    vm.LessonForm.subject_id = obj.subject_id;
                    vm.LessonForm.id = obj.id;
                    vm.content = obj.content;
                } else {
                    vm.$notify({
                        type: 'error',
                        group: 'foo',
                        title: 'Error',
                        text: 'Ups algo salio mal...!'
                    });
                    vm.$router.push({name: 'home'});
                }
            }).finally(() => {
                vm.loadForm = false;
            });
        },
        mounted() {
            
        }
    }
</script>
<style>
.ql-container.ql-snow{
    height: 450px;
}
</style>