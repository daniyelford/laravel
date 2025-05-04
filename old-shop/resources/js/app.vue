<template>
  <div id="app">
    <main>
      <router-view></router-view> <!-- This is where the routed components will be displayed -->
    </main>
  </div>
</template>



<style scoped>
/* Global styles for your application */
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}

header {
  background-color: #333;
  padding: 10px;
  color: white;
}

nav ul {
  list-style-type: none;
  padding: 0;
}

nav ul li {
  display: inline;
  margin-right: 15px;
}

footer {
  background-color: #333;
  color: white;
  padding: 10px;
  position: fixed;
  width: 100%;
  bottom: 0;
  text-align: center;
}
</style>
