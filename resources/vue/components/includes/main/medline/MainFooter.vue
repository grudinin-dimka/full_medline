<template>
	<footer>
		<!-- Верхняя секция -->
		<section class="up">
			<!-- Блок с платёжными системами -->
			<div class="pay">
				<a href="/">
					<img
						src="../../../../assets/svg/medline/logo-full.svg"
						width="250"
						height="150"
						alt="логотип"
					/>
				</a>
				<p>Мы принимаем к оплате</p>
				<div class="pay-cards">
					<!-- Карта visa -->
					<div class="card">
						<icon-visa :width="60" :height="60" />
					</div>
					<!-- Карта mastercard -->
					<div class="card">
						<icon-master-card :width="50" :height="40" />
					</div>
					<!-- Карта mir -->
					<div class="card">
						<icon-mir :width="60" :height="40" />
					</div>
				</div>
			</div>
			<!-- Блок с кнопками -->
			<div class="buttons">
				<a @click.prevent="$router.push({ name: `news` })" :href="`/news`" alt="контакты">
					Новости
				</a>
				<a @click.prevent="$router.push({ name: `about` })" :href="`/about`" alt="о нас">
					О нас
				</a>
				<a
					@click.prevent="$router.push({ name: `contacts` })"
					:href="`/contacts`"
					alt="контакты"
				>
					Контакты
				</a>
			</div>
			<!-- Блок доп. информации -->
			<div class="more">
				<!-- <div class="button" @click="">Записаться на прием</div> -->
				<p>Присоединяйтесь к нам</p>
				<div class="links">
					<a href="https://ok.ru/profile/586067527945" class="ok" target="_blank">
						<svg viewBox="0 0 198 342" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M99.2011 51.5C108.881 51.5 118.165 55.3455 125.01 62.1906C131.856 69.0357 135.701 78.3196 135.701 88C135.701 97.6804 131.856 106.964 125.01 113.809C118.165 120.654 108.881 124.5 99.2011 124.5C89.5207 124.5 80.2367 120.654 73.3917 113.809C66.5466 106.964 62.7011 97.6804 62.7011 88C62.7011 78.3196 66.5466 69.0357 73.3917 62.1906C80.2367 55.3455 89.5207 51.5 99.2011 51.5ZM99.2011 176C147.801 176 187.201 136.5 187.201 88C187.201 39.5 147.701 0 99.2011 0C50.7011 0 11.2011 39.5 11.2011 88C11.2011 136.5 50.7011 176 99.2011 176ZM134.801 247.9C152.86 243.815 170.101 236.715 185.801 226.9C188.664 225.088 191.142 222.729 193.093 219.96C195.045 217.19 196.432 214.063 197.174 210.757C197.917 207.451 198.002 204.031 197.423 200.693C196.844 197.355 195.613 194.163 193.801 191.3C191.989 188.437 189.631 185.959 186.861 184.008C184.091 182.056 180.964 180.67 177.658 179.927C174.352 179.184 170.932 179.099 167.594 179.678C164.256 180.257 161.064 181.488 158.201 183.3C140.418 194.413 119.871 200.305 98.9011 200.305C77.9316 200.305 57.384 194.413 39.6011 183.3C36.7383 181.488 33.5465 180.257 30.2081 179.678C26.8697 179.099 23.4499 179.184 20.1441 179.927C16.8383 180.67 13.7112 182.056 10.9414 184.008C8.17157 185.959 5.81324 188.437 4.00107 191.3C2.18873 194.163 0.958025 197.355 0.379229 200.693C-0.199566 204.031 -0.115126 207.451 0.627749 210.757C1.37062 214.063 2.75738 217.19 4.70882 219.96C6.66026 222.729 9.13818 225.088 12.0011 226.9C27.9011 236.9 45.0011 243.9 63.0011 247.9L14.0011 297.2C9.16087 302.04 6.44167 308.605 6.44167 315.45C6.44167 322.295 9.16087 328.86 14.0011 333.7C18.8413 338.54 25.406 341.259 32.2511 341.259C39.0961 341.259 45.6609 338.54 50.5011 333.7L98.9011 285.2L147.301 333.6C149.697 335.998 152.542 337.9 155.673 339.198C158.805 340.495 162.161 341.163 165.551 341.163C168.941 341.163 172.297 340.495 175.429 339.198C178.56 337.9 181.405 335.998 183.801 333.6C186.199 331.204 188.101 328.359 189.399 325.228C190.696 322.096 191.364 318.74 191.364 315.35C191.364 311.96 190.696 308.604 189.399 305.472C188.101 302.341 186.199 299.496 183.801 297.1L134.501 247.8"
							/>
						</svg>
					</a>
					<a href="https://vk.com/med_line45" class="vk" target="_blank">
						<svg viewBox="0 0 317 181" xmlns="http://www.w3.org/2000/svg">
							<path
								fill-rule="evenodd"
								clip-rule="evenodd"
								d="M155.096 180.242H174.044C174.044 180.242 179.768 179.613 182.69 176.46C185.379 173.566 185.293 168.13 185.293 168.13C185.293 168.13 184.923 142.683 196.723 138.936C208.356 135.245 223.293 163.53 239.126 174.406C251.098 182.637 260.196 180.832 260.196 180.832L302.529 180.242C302.529 180.242 324.675 178.877 314.174 161.45C313.314 160.024 308.059 148.558 282.699 124.998C256.155 100.339 259.709 104.327 291.684 61.6731C311.158 35.6963 318.943 19.8371 316.511 13.0446C314.192 6.57518 299.87 8.28514 299.87 8.28514L252.204 8.58234C252.204 8.58234 248.671 8.09993 246.05 9.66776C243.489 11.2054 241.841 14.7891 241.841 14.7891C241.841 14.7891 234.297 34.8908 224.236 51.9862C203.011 88.0591 194.524 89.9629 191.056 87.7232C182.987 82.5028 185.001 66.7513 185.001 55.5612C185.001 20.6038 190.298 6.02817 174.685 2.25505C169.504 1.00165 165.691 0.174663 152.44 0.0411394C135.433 -0.135457 121.038 0.0928203 112.887 4.08992C107.465 6.74747 103.282 12.6699 105.829 13.0102C108.979 13.4323 116.115 14.9355 119.897 20.0912C124.782 26.7416 124.61 41.679 124.61 41.679C124.61 41.679 127.416 82.8302 118.055 87.9428C111.63 91.4489 102.817 84.2903 83.8943 51.5641C74.1985 34.8004 66.8783 16.2707 66.8783 16.2707C66.8783 16.2707 65.4668 12.8077 62.9492 10.9556C59.8938 8.71155 55.6247 7.99657 55.6247 7.99657L10.3304 8.29375C10.3304 8.29375 3.53088 8.48328 1.03485 11.4423C-1.18575 14.0741 0.858408 19.5184 0.858408 19.5184C0.858408 19.5184 36.3192 102.553 76.4708 144.398C113.287 182.762 155.096 180.242 155.096 180.242Z"
							/>
						</svg>
					</a>
					<a href="https://t.me/medlinegroup_ru" class="telegram" target="_blank">
						<svg viewBox="0 0 259 214" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M3.90042 99.4869C30.3196 84.9344 59.8107 72.7888 87.3658 60.581C134.771 40.5857 182.365 20.9369 230.439 2.64406C239.792 -0.47286 256.598 -3.52104 258.246 10.3403C257.344 29.9609 253.632 49.4665 251.086 68.9722C244.623 111.867 237.154 154.615 229.87 197.368C227.361 211.609 209.521 218.982 198.106 209.868C170.673 191.338 143.03 172.989 115.947 154.029C107.076 145.015 115.302 132.07 123.225 125.632C145.82 103.366 169.781 84.4476 191.195 61.0302C196.971 47.0813 179.904 58.8371 174.275 62.4389C143.343 83.7545 113.168 106.371 80.5566 125.105C63.8985 134.275 44.4832 126.438 27.8327 121.321C12.9033 115.14 -8.97217 108.912 3.90042 99.4869Z"
							/>
						</svg>
					</a>
				</div>
			</div>
		</section>

		<!-- Нижняя секция -->
		<section class="bottom" :class="{ skeleton: loading.loader.footer }">
			<VueTiptap
				v-if="!loading.loader.footer"
				v-model="footer"
				:editable="false"
				:limit="10_000"
				:minHeight="50"
				:options="['format', 'align', 'list', 'link']"
				:placeholder="'Введите текст футера'"
			/>
		</section>

		<article>Имеются противопоказания Необходима консультация специалиста</article>
	</footer>
</template>

<script>
import Empty from "../../../modules/Empty.vue";
import VueTiptap from "../../../modules/VueTiptap.vue";

import IconVisa from "../../../icons/IconVisa.vue";
import IconMasterCard from "../../../icons/IconMasterCard.vue";
import IconMir from "../../../icons/IconMir.vue";

import axios from "axios";
import api from "../../../../mixin/api";

export default {
	components: {
		VueTiptap,
		Empty,

		IconVisa,
		IconMasterCard,
		IconMir,
	},
	data() {
		return {
			loading: {
				loader: {
					footer: true,
				},
				sections: {
					footer: false,
				},
			},
			nameTitle: "",
			footer: "",
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.footer = true;
		},

		goToContacts() {
			this.$router.push({ name: "contacts" });

			// скролл к верху
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "instant",
			});
		},
	},
	mounted() {
		let headers = {};

		if (document.referrer) {
			const referrer = document.referrer;

			headers["Custom-Referer"] = referrer;
		}

		api({
			method: "get",
			url: this.$store.getters.urlApi + "get-footer",
			headers: headers,
		}).then((response) => {
			if (!response) return;

			try {
				this.footer = response.data.result;

				this.loading.loader.footer = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		});
	},
};
</script>

<style scoped>
footer {
	width: 100%;
	display: flex;
	flex-direction: column;
}

section.up {
	display: flex;
	flex-direction: row;
	justify-content: space-around;
	flex-wrap: wrap;
	gap: 30px;

	text-transform: uppercase;

	margin: 0px 30px 20px 30px;
}

section.up > div {
	border-radius: var(--default-border-radius);
}

section.up .pay {
	flex-grow: 1;

	display: flex;
	flex-direction: column;
	text-transform: uppercase;
	justify-content: center;
	align-items: center;
}

section.up .pay p {
	margin: 10px;
	font-weight: bold;
	text-align: center;
	font-size: 12px;
}

.pay-cards {
	display: flex;
	gap: 10px;
}

.card {
	display: flex;
	justify-content: center;
	align-items: center;

	border-radius: calc(var(--default-border-radius) / 2);

	width: 80px;
	height: 50px;
}

.card:nth-child(1) {
	background-color: #cbe3f3;
}

.card:nth-child(2) {
	background-color: #f5dccd;
}

.card:nth-child(3) {
	background-color: #c5f5e3;
}

section.up .buttons {
	flex-grow: 1;

	display: flex;
	flex-direction: column;
	justify-content: center;
	gap: 20px;
}

section.up .buttons a {
	align-self: center;
	cursor: pointer;
	text-align: center;

	border: var(--input-border);
	border-radius: var(--button-border-radius);
	margin: 5px;

	text-transform: uppercase;
	text-decoration: none;
	font-size: 20px;

	color: black;
	background-color: white;
	padding: 15px 30px;
	min-width: 200px;

	transition: background-color 0.15s;
}

section.up .buttons a:hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

section.up .more {
	flex-grow: 1;

	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
}

section.up .more .button {
	align-self: center;
	cursor: pointer;
	text-align: center;

	border: none;
	border-radius: 5px;
	margin: 5px;

	text-transform: uppercase;
	font-size: 34px;

	color: white;
	background-color: var(--button-default-color);
	padding: 15px 30px;
	min-width: 200px;

	transition: background-color 0.15s;
}

section.up .more .button:hover {
	background-color: var(--button-default-color-hover);
}

section.up .more p {
	margin: 10px;
	text-align: center;
	font-weight: bold;
	font-size: 22px;
}

section.up .more .links {
	display: flex;
	gap: 10px;
	justify-content: space-between;

	text-align: center;
	font-weight: bold;
}

section.up .more .links > a {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	padding: 20px;
	border-radius: 100px;

	border: 5px solid var(--primary-color);
	transition: all 0.2s;
}

section.up .more .links > a > svg {
	width: 45px;
	height: 45px;
	fill: var(--primary-color);
}

section.up .more .links > a:hover {
	background-color: var(--primary-color);
}

section.up .more .links > a:hover > svg {
	fill: white;
}

section.bottom {
	min-height: 200px;
	padding: 20px;
	margin: 0px 30px 20px 30px;
	border-radius: calc(var(--default-border-radius) / 2);

	font-size: 20px;

	background-color: var(--skeleton-background-color);
}

footer article {
	text-align: center;

	border-radius: calc(var(--default-border-radius) / 2);
	margin-bottom: 10px;

	text-transform: uppercase;
	font-size: 2.4vw;

	color: rgb(158, 130, 130);
}

@media screen and (width <= 750px) {
	section.bottom {
		margin: 0px 20px 20px 20px;
	}
}

@media screen and (max-width: 500px) {
	footer a[href="/"] {
		display: none;
	}

	section.up .more .links > a > svg {
		width: 30px;
		height: 30px;
	}

	section.up .more .links > a {
		padding: 15px;
	}

	section.up .more .links {
		justify-content: space-evenly;
	}
}
</style>
