<template>
   <form class="login-form" @submit.prevent="submitForm">
      <!-- Тело формы -->
      <div class="form-body">
         <!-- Фио Пациента -->
         <div class="form-body-block">
            <label>ФИО*</label>
            <input v-model="inputs.pacient.value" type="text" placeholder="Пациент" @blur="checkPacient" />
            <div class="validError" v-if="errors.pacient.status">
               Поле обязательное для заполнения
            </div>
         </div>
         <!-- Номер телефона -->
         <div class="form-body-block">
            <label>Номер телефона*</label>
            <input type="tel" v-mask="'+7(###)-###-##-##'" placeholder="+7(___)___-__-__" v-model="inputs.phone.value"
               @blur="checkPhone" @input="checkPhone" />
            <div class="validError" v-if="errors.phone.status">
               {{ errors.phone.value }}
            </div>
         </div>
         <div class="form-body-block">
            <!-- Дата рождения -->
            <label>Дата рождения</label>
            <input v-model="inputs.date.value" type="date" class="date" />
         </div>
         <!-- Специальность врача -->
         <div class="form-body-block">
            <label>Специальность врача</label>
            <select name="spec" v-model="inputs.spec.value">
               <option value="" selected>-- Выберите специальность Врача --</option>
               <option value="Терапевт">Терапевт</option>
               <option value="Невролог">Невролог</option>
               <option value="Уролог">Уролог</option>
               <option value="Хирург">Хирург</option>
            </select>
         </div>
         <div class="form-body-block">
            <div>
               <input type="checkbox" id="scales" name="scales" v-model="inputs.check.value" />
               <label for="scales" :class="{ 'invalidCheckbox': errors.check.status }">
                  Согласен на обработку персональных данных в соответствии с
                  законодательством Российской Федерации*
               </label>
            </div>
         </div>
      </div>
      <div class="form-buttons">
         <button class="form-buttons-submit" type="submit" 
            @click="submitForm">
            Отправить заявку
         </button>
      </div>
   </form>
</template>

<script>
export default {
   data() {
      return {
         inputs: {
            pacient: {
               value: "",
            },
            phone: {
               value: "",
            },
            date: {
               value: "",
            },
            spec: {
               value: "",
            },
            check: {
               value: false,
            },
         },
         errors: {
            pacient: {
               status: false,
               value: "",
            },
            phone: {
               status: false,
               value: "",
            },
            date: {
               status: false,
               value: "",
            },
            spec: {
               status: false,
               value: "",
            },
            check: {
               status: false,
               value: "",
            },
         },
      };
   },
   methods: {
      /* -------------------------------------*/
      /* ----------Изменение формы------------*/
      /* -------------------------------------*/
      // Очистка полей
      clearInputs() {
         for (let key in this.inputs) {
            if (key !== "spec") this.inputs[key].value = "";
         }
      },
      // Очистка ошибок
      clearErrors() {
         for (let key in this.errors) {
            this.errors[key].status = false;
            this.errors[key].value = "";
         }
      },
      // Обновление формы
      resetForm() {
         this.clearInputs();
         this.clearErrors();
      },

      /* -------------------------------------*/
      /* -----------Контроль формы------------*/
      /* -------------------------------------*/
      // Проверка поля "пациент" 
      checkPacient() {
         if (this.inputs.pacient.value.length === 0) {
            this.errors.pacient.value = 'Поле не может быть пустым.';
            this.errors.pacient.status = true;
            return true;
         } else {
            this.errors.pacient.status = false;
            return false;
         };
      },
      // Проверка поля "телефон" 
      checkPhone() {
         if (this.inputs.phone.value.length === 0) {
            this.errors.phone.value = 'Поле не может быть пустым.';
            this.errors.phone.status = true;
            return true;
         } else if (this.inputs.phone.value.length < 17) {
            this.errors.phone.value = 'Номер не корректен.';
            this.errors.phone.status = true;
            return true;
         } else {
            this.errors.phone.status = false;
            return false;
         };
      },
      // Проверка поля "пациент" 
      checkCheckbox() {
         if (this.inputs.check.value == false) {
            this.errors.check.status = true;
            return true;
         } else {
            this.errors.check.status = false;            
            return false;
         }
      },
      // Проверка полей 
      checkInputsAll() {
         if (this.checkPacient() || this.checkPhone() || this.checkCheckbox()) {
            return true;
         } else {
            false;
         };
      },
      submitForm() {
         if (!this.checkInputsAll()) {
            this.$store.commit("changeModal");
            this.resetForm();            
         };
      },
   },
};
</script>

<style scoped>
* {
   margin: 0;
   padding: 0;
   box-sizing: border-box;
}

.newcheck {
   display: flex;
   justify-content: center;
}

form {
   display: flex;
   flex-direction: column;
}

.form-body {
   display: flex;
   flex-direction: column;
}

.form-body-block {
   display: flex;
   flex-direction: column;
}

.form-body-block label {
   margin: 5px 0px;
   font-size: 18px;
}

.form-body-block div label {
   text-align: justify;
}

.form-body-block input {
   outline: none;
   border: 2px solid var(--input-border-color-inactive);
   border-radius: 10px;
   padding: 15px;

   font-size: 18px;
   transition: all 0.15s;
}

.form-body-block:last-child {
   margin: 15px 0px;
}

.form-body-block input:focus {
   border: 2px solid var(--input-border-color-active);
}

.form-body-block input.invalidText {
   border: 2px solid red;
}

.form-body-block select {
   outline: none;
   border: 2px solid var(--input-border-color-inactive);
   border-radius: 10px;
   padding: 15px;

   font-size: 18px;
   transition: all 0.15s;
}

.form-body-block select:focus {
   border: 2px solid var(--input-border-color-active);
}

.invalidCheckbox {
   color: red;
}

.form-buttons {
   display: flex;
   justify-content: flex-end;
}

.form-buttons button {
   cursor: pointer;
}

.form-buttons-submit {
   padding: 15px;
   font-size: 18px;
   border: 0px;
   border-radius: 10px;
   background-color: var(--button-default-color);
   color: white;
}

.form-buttons-submit:hover {
   background-color: var(--button-default-color-hover);
}

.validError {
   color: red;
   margin-top: 5px;
}
</style>
