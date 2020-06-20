<template>
    <div>
         <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav text-uppercase">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link">Clases</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/subjects" class="nav-link">Asignaturas</router-link>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container-fluid">
            <router-view></router-view>
        </div>
        <notifications group="foo" position="bottom right"/>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.moment.locale('es'); 
        }
    }
</script>
<style>
.vue-notification .info {
    background: #387BEC;
    border-left-color: #2960BB;
}
</style>