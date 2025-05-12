<template>
  <div id="app">
    <!-- Global Header can go here -->
    <header>
      <nav>
        <ul>
          <li><router-link to="/">Login</router-link></li>
          <li><router-link to="/register">Register</router-link></li>
          <li><router-link to="/verify">Verify</router-link></li>
          <li><router-link to="/dashboard">Dashboard</router-link></li>
        </ul>
      </nav>
    </header>
    
    <!-- Main content area that changes based on route -->
    <main>
      <router-view></router-view> <!-- This is where the routed components will be displayed -->
    </main>

    <!-- Global Footer can go here -->
    <footer>
      <p>&copy; 2025 Your Company</p>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'App',
};
</script>

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
