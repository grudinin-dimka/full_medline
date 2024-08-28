<template>
  <aside :class="{ active: $store.state.burger.status }">
    <div class="aside-body">
      <admin-aside-list :links="links"/>
    </div>
    <div class="aside-footer">
      <button 
        class="btn-close"
        @click="logoutUser"
      >
        Выйти
      </button>
    </div>
  </aside>
</template>

<script>
import axios from 'axios';
import AdminAsideList from './AdminAsideList.vue';

export default {
  components: {
    axios,
    AdminAsideList
  },
  data() {
    return {
      links: [
        { 
          id: 1,
          name: 'Главная', 
          link: 'ehome'
        },
        {
          id: 2,
          name: 'О нас', 
          link: 'eabout'
        },
        {
          id: 3,
          name: 'Специалисты', 
          link: 'especialists'
        },
        {
          id: 4,
          name: 'Прайс', 
          link: 'eprice'
        },
      ],      
    }
  },
  methods: {
    // Выход из аккаунта
    logoutUser() {
      axios({
        method: 'post',
        url: `${this.$store.state.axios.urlApi}` + `logout`, 
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${localStorage.getItem('token')}`
        },
        data: {
          token: localStorage.getItem('token'),
        }
      });
      this.$store.state.burger.status = false;
      localStorage.removeItem('token');
      this.$router.push('/login');
      return;
    }
  }
}
</script>

<style scoped>
  aside {
    position: fixed;
    z-index: 100;
    display: flex;
    flex-direction: column;
    justify-content: space-between; 

    width: 350px;
    bottom: 0px;
    top: 80px;

    background-color: var(--primary-color);
    box-shadow: 0px 0px 20px -5px rgba(0, 0, 0, 0.3);

    transition: all 0.5s;
  }

  aside.active {
    padding-top: 100px;
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 0;
  }


  .aside-body {
    padding: 20px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 5px;
  }

  .aside-footer {
    padding: 20px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 5px;
  }

  .aside-footer button {
    cursor: pointer;
  }

  .btn-close {
    padding: 18px 0px;
    border: 0px;
    border-radius: 10px;

    text-align: center;
    font-size: 22px;
    text-decoration: none;

    color: white;
    background-color: rgba(0, 0, 0, 0.15);

    transition: all 0.15s ease-in-out;
  }

  .btn-close:hover {
    background-color: rgba(0, 0, 0, 0.3);
  }

  @media screen and (max-width: 1000px) {
    aside {
      z-index: 300;
      top: -100vh;
      bottom: 100vh;
      left: 0px;
      right: 0px;

      width: 100%;
    }
  }
</style>