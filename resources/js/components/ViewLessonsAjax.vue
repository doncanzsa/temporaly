<template>
    <div>
        <h3 class="text-center">Vista Clases AJAX</h3><br/>
        
        <div class="form-group row">
            <label for="subject_id" class="col-sm-3 col-form-label text-right">Seleccione Asignatura
                <div v-show="loadingSubjects" class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </label>
            <div class="col-sm-6">
                <select name="subject_id" :disable="(loadingSubjects) ? 'disabled': null" class="form-control" v-model="subject_id">
                    <option v-for="subject in Subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
                </select>
            </div>
            <div class="col-sm-3">
                <div v-show="loadingDataInfo">
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
            <div class="col-md-6">
                <label>Resultados: <b>{{Lessons.length}}</b></label>
            </div>
            <div class="col-md-6 text-right mb-3">
                <input type="search" class="form-control" id="searchAjax" v-model="searchAjax" placeholder="Buscar Clase Hot Reload">
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>ASIGNATURA</th>
                <th>TEMA</th>
                <th>CONTENIDO</th>
                <th>MODIFICACION</th>
                <th class="text-center">OPCIONES</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="loadingDataInfo"><td class="text-center font-weight-bold" colspan="6">Cargando Datos... </td></tr>
            <tr v-if="!loadingDataInfo && loadingDataInfo.length==0"><td class="text-center font-weight-bold" colspan="6">No hay datos...</td></tr>
            <tr v-for="(lesson, index) in Lessons" :key="lesson.id" @click.prevent="showInModal(index)">
                <td>{{ lesson.id }}</td>
                <td>{{ lesson.subjects.name }}</td>
                <td>{{ lesson.theme }}</td>
                <td>{{ (lesson.content.replace(/<[^>]*>?/g, '')).substr(0,100) }}</td>
                <td>{{ moment(lesson.updated_at).format('LLL') }}</td>
                <td class="text-center">
                    -- NONE --
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
    // import moment from 'moment'
    
    export default {
        components: {
        },
        data() {
            return {
                subject_id  : null,
                Lessons    : [],
                loadingDataInfo : false,
                loadingSubjects: false,
                Subjects: null,

                searchAjax: '',
                timeController : 500,
            }
        },
        watch: {
            searchAjax: function(val, oldVal) {
                this.loadDataAjax();
                
            },
            subject_id: function(val, oldVal) {
                let vm = this;
                if (vm.subject_id != null && vm.subject_id != '') {
                    // vm.loadDataAjax(vm.subject_id);
                }
            },
        },
        methods: {
            showInModal(idx) {

            },
            loadDataAjax() {
                let vm = this;
                clearTimeout(vm.timeController);
                vm.timeController = setTimeout(() =>
                {
                    console.log(vm.searchAjax);
                    vm.getLessons();
                }
                , 500);
            },
            getSubjects() {
                let vm = this;
                vm.loadingSubjects = true;
                vm.axios
                .get('/api/subjects')
                .then(response => {
                    vm.Subjects = response.data;
                    vm.loadingSubjects = false;
                }).catch(err => {
                    vm.loadingSubjects = false;
                    console.log(err);
                });
            },
            getLessons() {
                let vm = this;
                vm.loadingDataInfo = true;
                let params = {
                    id: vm.subject_id,
                    search: vm.searchAjax
                };
                vm.axios
                .get('/api/lessons/search/', {params})
                .then(response => {
                    vm.Lessons = response.data;
                    vm.loadingDataInfo = false;
                }).catch(err => {
                    vm.loadingDataInfo = false;
                    console.log(err);
                });
            }
        },
        mounted() {
            this.getSubjects();
        }
    }
</script>