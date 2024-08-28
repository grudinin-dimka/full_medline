<template>
   <div class="container-login">
      <div class="login">
         <div class="logo">
            <Logo />
         </div>
         <form @submit.prevent>
            <div class="block">
               <label>Логин</label>
               <input v-model="name" @blur="checkName" type="text" placeholder="Ваш логин"
                  :class="{ error: errors.name.status }" />
               <span v-if="errors.name.status">{{ errors.name.value }}</span>
            </div>
            <div class="block">
               <label>Пароль</label>
               <input v-model="password" @blur="checkPassword" type="password" placeholder="Ваш пароль"
                  :class="{ error: errors.password.status }" />
               <span v-if="errors.password.status">{{ errors.password.value }}</span>
            </div>
            <p v-if="errors.server" class="error">Неверный логин или пароль.</p>
            <div class="buttons">
               <ButtonDefault @click="loginUser"> Войти </ButtonDefault>
            </div>
         </form>
      </div>
   </div>
</template>

<script>
import { RouterLink, RouterView } from "vue-router";
import axios from "axios";

import Logo from "./icons/logo.vue";
import ButtonDefault from "./ui/ButtonDefault.vue";

export default {
   beforeCreate() {
      if (localStorage.getItem("token")) {
         // Проверка токена
         axios({
            method: 'post',
            url: `${this.$store.state.axios.urlApi}` + `check-token`,
            headers: {
               'Accept': 'application/json',
               'Authorization': `Bearer ${localStorage.getItem('token')}`,
            }
         }).catch((error) => {
            localStorage.removeItem("token");
            return;
         });

         // Перевод на страницу админки
         this.$router.push("/admin/ehome");
         return;
      };
   },
   components: {
      axios,
      RouterLink,
      RouterView,
      Logo,
      ButtonDefault,
   },
   data() {
      return {
         name: "",
         password: "",
         errors: {
            name: {
               status: false,
               value: "",
            },
            password: {
               status: false,
               value: "",
            },
            server: false,
         },
      };
   },
   methods: {
      // Проверка поля email
      checkName() {
         // Пустота
         if (this.name === "") {
            this.errors.name.status = true;
            this.errors.name.value = "Поле не может быть пустым";
            return true;
         }

         this.errors.name.status = false;
         this.errors.name.value = "";
         return false;
      },

      // Проверка поля email
      checkPassword() {
         // Пустота
         if (this.password === "") {
            this.errors.password.status = true;
            this.errors.password.value = "Поле не может быть пустым";
            return true;
         }

         this.errors.password.status = false;
         this.errors.password.value = "";
         return false;
      },

      // Проверка всех полей
      checkAllInputs() {
         let errors = 0;

         if (this.checkName()) errors++;
         if (this.checkPassword()) errors++;

         if (errors !== 0) return false;
         else return true;
      },

      // Авторизация
      loginUser() {
         if (this.checkAllInputs()) {
            axios({
               method: "post",
               url: `${this.$store.state.axios.urlApi}` + `login`,
               data: {
                  name: this.name,
                  password: this.password,
               },
            }).then((response) => {
               // Если сервер овтетит false
               if (!response.data.status) {
                  this.errors.server = true;
                  console.log("Неверный логин или пароль.");
                  return;
               } else {
                  this.errors.server = false;
               }

               // Если сервер овтетит true
               if (response.data.status) {
                  // Запись токена в глобальную переменную
                  localStorage.setItem("token", response.data.token);

                  axios.defaults.headers.common['Accept'] = 'application/json';
                  axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

                  // Вводим первоначальные данные активной ссылки
                  localStorage.setItem('linkActive', 'ehome');
                  localStorage.setItem('linkPrevios', 'ehome');

                  // Перевод на страницу админки
                  this.$router.push("/admin/ehome");

                  // Очистка ошибок
                  this.errors.name.status = false;
                  this.errors.name.value = "";
                  this.errors.password.status = false;
                  this.errors.password.value = "";

                  return;
               }
            })
               .catch((error) => {
                  this.errors.server = true;
                  console.log("Отсутствует соединение с сервером.");
                  return;
               });
         };
      },
   },
};
</script>

<style scoped>
.container-login {
   display: flex;
   justify-content: center;
   align-items: center;
   height: 100vh;
   background-color: #ECECEC;
}

.login {
   display: flex;
   flex-direction: column;

   border-radius: 20px;

   padding: 40px;
   margin: 20px;

   width: 500px;
   background: white;

   box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.10);
}

.logo {
   display: flex;
   justify-content: center;
   margin-bottom: 30px;
}

.block {
   display: flex;
   flex-direction: column;
}

.block label {
   margin: 10px 10px;

   font-size: 20px;
}

.block input {
   box-sizing: border-box;
   outline: none;

   padding: 10px;
   border: 2px solid var(--input-border-color-inactive);
   border-radius: 10px;

   height: 58px;

   font-size: 20px;
   caret-color: var(--input-border-color-active);

   transition: all 0.2s;
}

.block input:focus {
   border: 2px solid var(--input-border-color-active);
}

.block input.error {
   background-color: var(--input-background-color-error);
   border: 2px solid var(--input-border-color-error);

   caret-color: red;
}

span {
   margin-top: 5px;
   margin-left: 5px;
   color: var(--span-color-error);
}

.buttons {
   display: flex;
   justify-content: center;

   margin-top: 30px;
}

.error {
   padding: 15px;
   border-radius: 10px;
   background-color: var(--input-background-color-error);
   border: 2px solid var(--input-border-color-error);
}

@media screen and (max-width: 620px) {
   .login {
      padding: 40px 20px;
   }
}
</style>
