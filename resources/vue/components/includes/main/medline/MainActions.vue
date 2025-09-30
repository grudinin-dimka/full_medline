<template>
	<!-- Модальное окно: Баллы -->
	<VueModal ref="modalPersonal" :settings="modalPersonal">
		<template #title>
			{{ modalPersonal.values.title }}
		</template>

		<template #body>
			<VueInputContainer :direction="'column'" :gap="'10px'">
				<template #legend> КОНТАКТНЫЕ ДАННЫЕ </template>
				<template #inputs>
					<VueInput
						v-model="modalPointsForm.data.name.value"
						:type="'text'"
						:placeholder="'Введите ФИО'"
						:error="modalPointsForm.errors.name.status"
					>
						<template #label> ФИО </template>
						<template #error>
							{{ modalPointsForm.errors.name.message }}
						</template>
					</VueInput>

					<VueInput
						v-model="modalPointsForm.data.snils.value"
						:type="'mask'"
						:mask="'###-###-###-##'"
						:placeholder="'___-___-___-__'"
						:error="modalPointsForm.errors.snils.status"
					>
						<template #label> НОМЕР СНИЛСА </template>
						<template #error>
							{{ modalPointsForm.errors.snils.message }}
						</template>
					</VueInput>
				</template>
			</VueInputContainer>

			<VueInputContainer :direction="'column'" :gap="'10px'">
				<template #legend> ПРОВЕРОЧНЫЙ КОД </template>
				<template #inputs>
					<div class="captcha">
						<div class="captcha__content">
							<div class="captcha__content-text">
								<span v-for="letter in getCaptchaSplited" :style="getLetterStyle()">
									{{ letter }}
								</span>

								<div class="content__text-line" ref="line"></div>
								<div class="content__text-trash"></div>
							</div>
							<div class="captcha__content-update" @click="reloadCaptcha">
								<svg
									xmlns="http://www.w3.org/2000/svg"
									height="26px"
									viewBox="0 -960 960 960"
									width="26px"
								>
									<path
										d="M480-160q-134 0-227-93t-93-227q0-134 93-227t227-93q69 0 132 28.5T720-690v-110h80v280H520v-80h168q-32-56-87.5-88T480-720q-100 0-170 70t-70 170q0 100 70 170t170 70q77 0 139-44t87-116h84q-28 106-114 173t-196 67Z"
									/>
								</svg>
							</div>
						</div>

						<VueInput
							v-model="modalPointsForm.data.captcha.value"
							:type="'text'"
							:placeholder="'Введите текст'"
							:error="modalPointsForm.errors.captcha.status"
						>
							<template #label> КОД С КАРТИНКИ </template>
							<template #error>
								{{ modalPointsForm.errors.captcha.message }}
							</template>
						</VueInput>
					</div>
				</template>
			</VueInputContainer>

			<VueInput
				v-model="modalPointsForm.data.checkbox.value"
				:type="'checkbox'"
				:error="modalPointsForm.errors.checkbox.status"
			>
				<template #label
					>Согласие на обработку указанных персональных данных в соответствии с
					законодательством Российской Федерации.</template
				>
			</VueInput>

			<div class="modal__result">
				<template v-if="barcodes.length > 0">
					<div
						class="modal__result-item"
						v-for="barcode in barcodes"
						:class="{ skeleton: disabled.modalPoints.request }"
					>
						<div class="result__item-header">
							<div class="result__header-title">{{ barcode.type }}</div>
							<div class="result__header-value">{{ barcode.value }}</div>
						</div>

						<div class="result__item-barcode">
							<vue-js-barcode
								:value="barcode.barcode_value"
								:width="'2rem'"
								:height="40"
								:format="barcode.barcode_type"
								line-color="#000000"
								background="white"
							></vue-js-barcode>
						</div>
					</div>
				</template>

				<template v-else> </template>
			</div>
		</template>

		<template #footer>
			<VueButton @click="sendClientPoints" :disabled="disabled.modalPoints.request">
				<VueIcon :name="'credit-card'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Узнать баланс
			</VueButton>
		</template>
	</VueModal>

	<!-- Модальное окно: Информация о персональных данных -->
	<VueModal ref="modalPersonal" :settings="modalPersonal">
		<template #title>
			{{ modalPersonal.values.title }}
		</template>

		<template #body>
			<VueTiptap :editable="false" :limit="10_000" v-model="personalText" />
		</template>

		<template #footer>
			<VueButton @click="this.$refs.modalPersonal.close()"> Закрыть </VueButton>
		</template>
	</VueModal>

	<!-- Модальное окно: Заявка -->
	<VueModal ref="modalRequest" :settings="modalRequest" @touch="sendRequest">
		<template #title>
			{{ modalRequest.values.title }}
		</template>

		<template #body>
			<VueInputContainer :direction="'column'" :gap="'10px'">
				<template #legend> КОНТАКТНЫЕ ДАННЫЕ </template>
				<template #inputs>
					<VueInput
						v-model="modalRequestForm.data.name.value"
						:type="'text'"
						:placeholder="'Введите ФИО'"
						:error="modalRequestForm.errors.name.status"
					>
						<template #label> ФИО </template>
						<template #error>
							{{ modalRequestForm.errors.name.message }}
						</template>
					</VueInput>

					<VueInput
						v-model="modalRequestForm.data.phone.value"
						:type="'phone'"
						:placeholder="'+7 (___) ___-__-__'"
						:error="modalRequestForm.errors.phone.status"
					>
						<template #label> НОМЕР ТЕЛЕФОНА </template>
						<template #error>
							{{ modalRequestForm.errors.phone.message }}
						</template>
					</VueInput>
				</template>
			</VueInputContainer>

			<VueInputContainer :direction="'column'" :gap="'10px'">
				<template #legend> ДРУГАЯ ИНФОРМАЦИЯ </template>
				<template #inputs>
					<VueInput
						v-model="modalRequestForm.data.description.value"
						:type="'textarea'"
						:placeholder="'Введите описание'"
						:error="modalRequestForm.errors.description.status"
					>
						<template #label> ОПИСАНИЕ </template>
						<template #error>
							{{ modalRequestForm.errors.description.message }}
						</template>
					</VueInput>

					<VueInput
						v-model="modalRequestForm.data.title.value"
						:type="'select'"
						:options="[
							{
								default: true,
								disabled: true,
								value: '',
								label: 'Выберите категорию',
							},
							{
								default: false,
								disabled: false,
								value: 'Запись к врачу',
								label: 'Запись к врачу',
							},
							{
								default: false,
								disabled: false,
								value: 'Вызов врач на дом',
								label: 'Вызов врач на дом',
							},
							{
								default: false,
								disabled: false,
								value: 'Другое',
								label: 'Другое',
							},
						]"
						:error="modalRequestForm.errors.title.status"
					>
						<template #label> ТИП ЗАЯВКИ </template>
						<template #error>
							{{ modalRequestForm.errors.title.message }}
						</template>
					</VueInput>
				</template>
			</VueInputContainer>

			<VueInputContainer :direction="'column'" :gap="'10px'">
				<template #legend> ПРОВЕРОЧНЫЙ КОД </template>
				<template #inputs>
					<div class="captcha">
						<div class="captcha__content">
							<div class="captcha__content-text">
								<span v-for="letter in getCaptchaSplited" :style="getLetterStyle()">
									{{ letter }}
								</span>

								<div class="content__text-line" ref="line"></div>
								<div class="content__text-trash"></div>
							</div>
							<div class="captcha__content-update" @click="reloadCaptcha">
								<svg
									xmlns="http://www.w3.org/2000/svg"
									height="26px"
									viewBox="0 -960 960 960"
									width="26px"
								>
									<path
										d="M480-160q-134 0-227-93t-93-227q0-134 93-227t227-93q69 0 132 28.5T720-690v-110h80v280H520v-80h168q-32-56-87.5-88T480-720q-100 0-170 70t-70 170q0 100 70 170t170 70q77 0 139-44t87-116h84q-28 106-114 173t-196 67Z"
									/>
								</svg>
							</div>
						</div>

						<VueInput
							v-model="modalRequestForm.data.captcha.value"
							:type="'text'"
							:placeholder="'Введите текст'"
							:error="modalRequestForm.errors.captcha.status"
						>
							<template #label> КОД С КАРТИНКИ </template>
							<template #error>
								{{ modalRequestForm.errors.captcha.message }}
							</template>
						</VueInput>
					</div>
				</template>
			</VueInputContainer>

			<VueInput
				v-model="modalRequestForm.data.checkbox.value"
				:type="'checkbox'"
				:error="modalRequestForm.errors.checkbox.status"
			>
				<template #label>
					Согласие на обработку указанных
					<a class="link" href="#" @click.prevent="openModalPersonalEdite">
						персональных данных
					</a>
					в соответствии с законодательством Российской Федерации.
				</template>
			</VueInput>
		</template>

		<template #footer>
			<VueButton @click="sendRequest" :disabled="disabled.modalForm.request">
				<VueIcon :name="'Phone Enabled'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Отправить заявку
			</VueButton>
		</template>
	</VueModal>

	<!-- Кнопки -->
	<div class="main__buttons">
		<button class="main__buttons-button" @click="openModalRequestEdite">
			<VueIcon :name="'Phone Enabled'" :fill="'white'" :width="'30px'" :height="'30px'" />
		</button>

		<button class="main__buttons-button" @click="topFunction">
			<VueIcon :name="'arrow'" :fill="'white'" :width="'26px'" :height="'26px'" />
		</button>
	</div>
</template>

<script>
import VueModal from "../../../modules/modal/VueModal.vue";
import VueInput from "../../../modules/input/VueInput.vue";
import VueInputContainer from "../../../modules/input/VueInputContainer.vue";
import VueTiptap from "../../../modules/VueTiptap.vue";

import Captcha from "../../../modules/Captcha.vue";
import VueIcon from "../../../modules/icon/VueIcon.vue";
import VueButton from "../../../ui/VueButton.vue";

import api from "../../../../mixin/api";
import validate from "../../../../services/validate";
import shared from "../../../../services/shared";

export default {
	components: {
		VueModal,
		VueInput,
		VueInputContainer,
		VueTiptap,

		Captcha,
		VueButton,
		VueIcon,
	},
	data() {
		return {
			countPoints: 0,

			/* Отключение */
			disabled: {
				modalForm: {
					request: false,
				},
				modalPoints: {
					request: false,
				},
			},

			/* Модальное окно */
			modalPersonal: {
				thin: false,
				clamped: true,
				touch: true,
				values: {
					title: "",
					look: "default",
				},
			},

			modalRequest: {
				thin: false,
				clamped: true,
				touch: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalPersonal: {
				zIndex: 501,
				thin: true,
				clamped: true,
				touch: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			modalPointsForm: {
				errors: {
					name: {
						status: false,
						message: "",
					},
					snils: {
						status: false,
						message: "",
					},
					checkbox: {
						status: false,
						message: "",
					},
					captcha: {
						status: false,
						message: "",
					},
				},
				data: {
					name: {
						value: "",
						edited: false,
					},
					snils: {
						value: "",
						edited: false,
					},
					checkbox: {
						value: false,
						edited: false,
					},
					captcha: {
						value: "",
						edited: false,
					},
					barcode_value: {
						value: "",
						edited: false,
					},
					barcode_type: {
						value: "",
						edited: false,
					},
					points: {
						value: "",
						edited: false,
					},
				},
			},

			modalRequestForm: {
				errors: {
					title: {
						status: false,
						message: "",
					},
					name: {
						status: false,
						message: "",
					},
					phone: {
						status: false,
						message: "",
					},
					email: {
						status: false,
						message: "",
					},
					description: {
						status: false,
						message: "",
					},
					checkbox: {
						status: false,
						message: "",
					},
					captcha: {
						status: false,
						message: "",
					},
				},
				data: {
					title: {
						value: "",
						edited: false,
					},
					name: {
						value: "",
						edited: false,
					},
					phone: {
						value: "",
						edited: false,
					},
					email: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
					checkbox: {
						value: false,
						edited: false,
					},
					captcha: {
						value: "",
						edited: false,
					},
				},
			},

			/* Данные */
			barcodes: [],
			captcha: "",

			personalText: `
				<p style="text-align: justify">Настоящим субъект персональных данных, именуемый в дальнейшем «Пользователь», в соответствии с Федеральным законом от 27.07.2006 №152-ФЗ «О персональных данных», отправляя сообщение через форму обратной связи на интернет-сайте <a href='https://medlinegroup.ru/'>https://medlinegroup.ru/</a>, указывая сведения о своих персональных данных и проставляя отметку «✓» (или аналогичную) в соответствующем чек-боксе, действуя свободно, своей волей и в своем интересе, а также подтверждая свою дееспособность, дает свое на обработку персональных данных (далее – Согласие) ООО «Медицинская линия», (далее - Оператор) расположенному по адресу: г. Шадринск, Комсом 16, оф 311, на обработку своих персональных данных со следующими условиями:</p>
				<p style="text-align: justify">Данное Согласие дается на обработку персональных данных, как без использования средств автоматизации, так и с их использованием.</p>
				<p style="text-align: justify">Согласие дается на обработку следующих персональных данных не являющиеся специальными или биометрическими: фамилия, имя, отчество; номера контактных телефонов.</p>
				<p style="text-align: justify">Персональные данные не являются общедоступными.</p>
				<p style="text-align: justify">Цель обработки персональных данных: осуществление звонков по запросу обратного звонка.</p>
				<p style="text-align: justify">В ходе обработки с персональными данными будут совершены следующие действия: сбор; запись; систематизация; накопление; хранение; уточнение (обновление, изменение); извлечение; использование; передача (распространение, предоставление, доступ); блокирование; удаление; уничтожение.</p>
				<p style="text-align: justify">Согласие может быть отозвано Пользователем или его представителем путем направления письменного заявления ООО «Медицинская линия» или его представителю по адресу, указанному в начале данного Согласия.</p>
				<p style="text-align: justify">Пользователь уведомлен, что в случае отзыва Согласия Оператор вправе продолжить обработку персональных данных без согласия Пользователя в случае, если это предусмотрено законодательством Российской Федерации.</p>
			`,
		};
	},
	computed: {
		/* Разделение капчи на отдельные символы */
		getCaptchaSplited() {
			let array = [];

			for (let i = 0; i < this.captcha.length; i++) {
				array.push(this.captcha[i]);
			}

			return array;
		},

		/* Разделение капчи на отдельные символы */
		filteredBarcodes() {
			let array = [];

			array = this.barcodes.map((value, index) => {
				return {
					default: false,
					disabled: false,
					value: value.id,
					label: value.type,
				};
			});

			array.unshift({
				default: true,
				disabled: true,
				value: "",
				label: "Выберите карту",
			});

			return array;
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾| */
		/* |            Кнопки для пользователей               | */
		/* |___________________________________________________| */
		topFunction() {
			window.scrollTo({ top: 0, behavior: "smooth" });
		},

		scrollFunction() {
			var backToTop = document.getElementById("back-to-top");

			if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
				backToTop.style.opacity = "1";
				backToTop.style.visibility = "visible";
			} else {
				backToTop.style.opacity = "0";
				backToTop.style.visibility = "hidden";
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                Модальное окно                     |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна баллов */
		openModalPointsEdite() {
			shared.clearObjectFull(this.modalPointsForm);
			this.reloadCaptcha();

			this.barcodes = [
				{
					id: 1,
					type: "Карта 1",
					value: "?",
				},
				{
					id: 2,
					type: "Карта 2",
					value: "?",
				},
			];

			this.openModal("modalPoints", "УЗНАТЬ БАЛЛЫ", "default");
		},

		/* Открытие модального окна заявки */
		openModalRequestEdite() {
			shared.clearObjectFull(this.modalRequestForm);
			this.reloadCaptcha();

			this.openModal("modalRequest", "ЗАКАЗАТЬ ЗВОНОК", "default");
		},

		/* Открытие модального окна заявки */
		openModalPersonalEdite() {
			this.openModal("modalPersonal", "ИНФОРМАЦИЯ", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     КАПТЧА                        |*/
		/* |___________________________________________________|*/
		/* Обновление */
		reloadCaptcha() {
			this.captcha = shared.generateRandomString(5);
			this.$refs.line.style.transform = `rotate(${shared.generateRandomAngle(25)}deg)`;
		},

		/* Стилизация */
		getLetterStyle() {
			return {
				transform: `translateY(${shared.generateRandomAngle(25)}deg)`,
				transform: `rotate(${shared.generateRandomAngle(25)}deg)`,
			};
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     ЗАТЫЧКИ                       |*/
		/* |___________________________________________________|*/
		sayHelloPoints() {
			switch (true) {
				case this.countPoints < 3:
					this.$store.commit("addDebugger", {
						title: "Упс...",
						body: "Это ещё не готово, но уже скоро будет :)",
						type: "completed",
					});

					this.countPoints++;
					break;

				case this.countPoints >= 3 && this.countPoints < 6:
					this.$store.commit("addDebugger", {
						title: "Ой!",
						body: "Всё-таки, это ещё не готово, но уже скоро будет :)",
						type: "completed",
					});

					this.countPoints++;
					break;

				case this.countPoints == 6:
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: "Вы сломали матрицу...",
						type: "error",
					});

					this.countPoints++;
					break;

				case this.countPoints == 7:
					this.$store.commit("addDebugger", {
						title: "ФЫык0_-йц0",
						body: "С#!@%^&* ()_+~",
						type: "error",
					});

					this.countPoints++;
					break;

				case this.countPoints == 8:
					this.$store.commit("addDebugger", {
						title: "КритичЕсК0й сб0й...",
						body: "╬╠╬╠╣╠╬",
						type: "error",
					});

					this.countPoints++;
					break;

				case this.countPoints >= 9:
					this.$store.commit("addDebugger", {
						title: "Шутка!",
						body: "Вы ничего не сломали! Но всё-таки, ещё не готово и вам следует подождать :)",
						type: "completed",
					});

					this.countPoints = 0;
					break;
			}
		},

		redirectToCabinet() {
			window.location.href = "https://lk.medlinegroup.ru/";
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 ОТПРАВКА ДАННЫХ                   |*/
		/* |___________________________________________________|*/
		/* Отправка запроса */
		sendRequest() {
			let errors = false;

			if (
				validate.checkInputsAll(this.modalRequestForm, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "phone",
						type: "phone",
					},
					{
						key: "title",
						type: "text",
					},
					{
						key: "checkbox",
						type: "boolean",
						reference: true,
					},
					{
						key: "captcha",
						type: "twice",
						reference: this.captcha,
					},
				])
			)
				errors++;

			if (errors) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: "Заполните важные поля.",
					type: "error",
				});

				return;
			}

			this.disabled.modalForm.request = true;

			let formData = new FormData();

			api({
				method: "post",
				url: this.$store.getters.urlApi + `request-telegram-bot`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: {
					title: this.modalRequestForm.data.title.value,
					name: this.modalRequestForm.data.name.value,
					phone: this.modalRequestForm.data.phone.value,
					description: this.modalRequestForm.data.description.value,
				},
			})
				.then((response) => {
					if (!response) return;

					this.$refs.modalRequest.close();
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.modalForm.request = false;
				});
		},

		/* Отправка запроса */
		sendClientPoints() {
			let errors = false;

			if (
				validate.checkInputsAll(this.modalPointsForm, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "snils",
						type: "text",
					},
					{
						key: "checkbox",
						type: "boolean",
						reference: true,
					},
					{
						key: "captcha",
						type: "twice",
						reference: this.captcha,
					},
				])
			)
				errors++;

			if (errors) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: "Заполните важные поля.",
					type: "error",
				});

				return;
			}

			this.disabled.modalPoints.request = true;
			this.barcodes = [
				{
					id: 1,
					type: "Карта 1",
					value: "?",
				},
				{
					id: 2,
					type: "Карта 2",
					value: "?",
				},
			];

			api({
				method: "post",
				url: this.$store.getters.urlApi + `clieint-points`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: {
					name: this.modalPointsForm.data.name.value,
					snils: this.modalPointsForm.data.snils.value,
				},
			})
				.then((response) => {
					if (!response) return;

					if (!response.data.result.length) {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: "К вам не привязана ни одна карта.",
							type: "error",
						});
					} else {
						for (let i = 0; i < response.data.result.length; i++) {
							this.barcodes[i] = response.data.result[i];
						}

						this.barcodes.splice(response.data.result.length, this.barcodes.length);
					}
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.modalPoints.request = false;
				});
		},
	},
	mounted() {
		window.addEventListener("scroll", this.setShadow);
	},
};
</script>

<style scoped>
/* Кнопки */
.main__buttons {
	position: fixed;
	bottom: 30px;
	right: 30px;
	z-index: 1;

	display: flex;
	flex-direction: column;
	gap: 10px;
}

.main__buttons-button {
	cursor: pointer;

	display: flex;
	justify-content: center;
	align-items: center;
	border: none;
	border-radius: 50%;
	outline: none;

	width: 70px;
	height: 70px;
	background-color: var(--primary-color);
	color: white;
	font-size: 16px;

	transition: all 0.2s ease-in-out;
	box-shadow: var(--default-shadow);
}

.main__buttons-button.rotate {
	transform: rotate(-90deg);
}

.main__buttons-button:hover {
	background-color: var(--primary-color-hover);
}

/* Баллы */
.modal__result {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: var(--default-gap);

	margin-top: 10px;

	border-radius: calc(var(--default-border-radius) / 1.5);
}

.modal__result-item {
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	justify-content: space-between;

	border-radius: calc(var(--default-border-radius) / 2);
	padding: var(--default-padding);

	min-height: 200px;
	background-color: var(--skeleton-background-color);
}

.result__item-header {
	display: flex;
	justify-content: space-between;

	font-size: 1.25rem;
	color: black;
}

.result__item-barcode {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;

	border-radius: calc(var(--default-border-radius) / 2);
	background-color: white;
	min-height: 100px;
}

@media screen and (width <= 800px) {
	.modal__result {
		grid-template-columns: repeat(1, 1fr);
	}
}

.checkbox > .captcha__content {
	display: flex;
	justify-content: center;
	font-size: 18px;
}

.checkbox.error > .captcha__content > label {
	color: var(--span-color-error);
}

.checkbox > .captcha__content > input {
	width: 17.5px;
	height: 17.5px;
}

.captcha__content > input:checked {
	accent-color: #8fe5ee;
}

.captcha {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 10px;

	width: 100%;
}

.captcha__content {
	width: 100%;
	position: relative;
}

.captcha__content-text {
	box-sizing: border-box;
	overflow: hidden;
	display: flex;
	justify-content: center;
	align-items: center;
	position: relative;
	user-select: none;
	border: var(--input-border);

	border-radius: var(--input-border-radius);
	color: rgb(0, 0, 0);

	height: 58px;

	font-family: "Henny Penny";
	font-weight: lighter;
	font-size: 2rem;
	letter-spacing: 10px;
}

.captcha__content-text > span {
	font-family: "Henny Penny";
	font-weight: lighter;
	font-size: 2rem;
}

.content__text-line {
	position: absolute;
	z-index: 0;
	width: 350px;
	height: 1px;

	background-color: rgb(140, 140, 140);
}

.content__text-trash {
	position: absolute;
	z-index: 0;
	width: 100%;
	height: 100%;

	background-image: url("../../../../assets/img/trash.webp");
	background-position: center center;
	background-size: contain;
	opacity: 0.7;
}

.captcha__content-update {
	position: absolute;
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	background-color: white;
	padding: 5px;
	border-radius: 100px;
	border: var(--default-border);

	top: 11px;
	right: 5px;
}

.captcha__content-update > svg {
	fill: rgb(0, 0, 0);
}

@media screen and (max-width: 750px) {
	.main__buttons {
		bottom: 20px;
		right: 20px;
	}
}
</style>
