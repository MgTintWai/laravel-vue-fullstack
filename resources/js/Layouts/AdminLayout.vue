<template>
  <div>
      <div class="content">
            <!-- Side Menu Bar -->
            <div class="row mx-auto px-auto justify-content-start">
                <div class="logo col-2">
                    <h2>logo image</h2>
                </div>
                <div class="col-10"> 
                </div>
            </div>
            <div class="row">
                <div class="col-2 text-center">
                    <h2 class="title">Admin</h2>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <router-link to="/">Dashboard</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/tag">Tags</router-link>
                        </li>
                    </ul>
                </div>
                <div class="col-10 main-content">
                    <slot />

                </div>
            </div>
        </div>
  </div>
</template>

<script>
export default {

}
</script>

<style>

</style>