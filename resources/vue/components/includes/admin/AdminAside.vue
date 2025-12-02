<template>
	<VueModal ref="modalExit" :settings="modalExit">
		<template #title>{{ modalExit.values.title }}</template>
		<template #body>Вы уверены, что хотите выйти из системы?</template>
		<template #footer>
			<VueButton :disabled="$store.getters.getLogoutStatus" @click="$store.dispatch('logout')">
				Выход
			</VueButton>
		</template>
	</VueModal>

	<aside :class="{ active: $store.getters.burgerAdminStatus }">
		<div class="aside__body">
			<div class="aside__item" :class="{ active: isActive('/admin/profile') }">
				<a
					class="aside__item-title"
					href="#"
					@click.prevent="$router.push({ name: 'profile' })"
				>
					<VueIcon :name="'Account Circle'" :fill="'white'" :width="'26px'" :height="'26px'" />
					ПРОФИЛЬ
				</a>
			</div>

			<template v-if="isCreator || isAdmin">
				<div class="aside__item" :class="{ active: isActive('/admin/ehome') }">
					<a
						class="aside__item-title"
						href="/admin/ehome"
						@click.prevent="$router.push({ name: 'ehome' })"
					>
						<VueIcon :name="'Home'" :fill="'white'" :width="'26px'" :height="'26px'" />
						ГЛАВНАЯ
					</a>
				</div>

				<div class="aside__item">
					<a
						class="aside__item-title"
						:class="{ active: lists.specialists.status }"
						href="#"
						@click.prevent="openList('specialists')"
					>
						<VueIcon :name="'Ecg Heart'" :fill="'white'" :width="'26px'" :height="'26px'" />
						<VueIcon
							class="item-arrow"
							:name="'Arrow'"
							:fill="'white'"
							:width="'20px'"
							:height="'20px'"
							:rotate="180"
						/>
						СПЕЦИАЛИСТЫ
					</a>

					<div
						class="item-list"
						ref="especialists"
						:class="{ active: lists.specialists.status }"
					>
						<a
							href="#"
							:class="{ active: isActive('/admin/especialists') }"
							@click.prevent="$router.push({ name: `especialists-all` })"
						>
							СПИСОК
						</a>
						<a
							href="#"
							:class="{ active: isActive('/admin/especialists/specializations') }"
							@click.prevent="$router.push({ name: `especialists-specializations` })"
						>
							СПЕЦИАЛИЗАЦИИ
						</a>
						<a
							href="#"
							:class="{ active: isActive('/admin/especialists/clinics') }"
							@click.prevent="$router.push({ name: `especialists-clinics` })"
						>
							КЛИНИКИ
						</a>
					</div>
				</div>

				<div class="aside__item">
					<a
						class="aside__item-title"
						:class="{ active: lists.prices.status }"
						href="#"
						@click.prevent="openList('prices')"
					>
						<VueIcon :name="'Payments'" :fill="'white'" :width="'26px'" :height="'26px'" />

						<VueIcon
							class="item-arrow"
							:name="'Arrow'"
							:fill="'white'"
							:width="'20px'"
							:height="'20px'"
							:rotate="180"
						/>
						ЦЕНЫ
					</a>
					<div class="item-list" ref="eprices" :class="{ active: lists.prices.status }">
						<a
							href="/admin/eprices"
							:class="{ active: isActive('/admin/eprices') }"
							@click.prevent="$router.push({ name: 'eprices-all' })"
						>
							СПИСОК ЦЕН
						</a>

						<a
							href="/admin/eprices/rules"
							:class="{ active: isActive('/admin/eprices/rules') }"
							@click.prevent="$router.push({ name: 'eprices-template' })"
						>
							ШАБЛОН
						</a>
					</div>
				</div>


				<div class="aside__item" :class="{ active: isActive('/admin/vacancies') }">
					<a
						class="aside__item-title"
						href="/admin/vacancies"
						@click.prevent="$router.push({ name: 'evacancies' })"
					>
						<VueIcon :name="'Badge'" :fill="'white'" :width="'28px'" :height="'28px'" />
						ВАКАНСИИ
					</a>
				</div>

				<div class="aside__item" :class="{ active: isActive('/admin/eschedule') }">
					<a
						class="aside__item-title"
						href="/admin/eschedule"
						@click.prevent="$router.push({ name: 'eschedule' })"
					>
						<VueIcon
							:name="'Calendar Month'"
							:fill="'white'"
							:width="'26px'"
							:height="'26px'"
						/>
						РАСПИСАНИЕ
					</a>
				</div>

				<div class="aside__item" :class="{ active: isActive('/admin/eclients') }">
					<a
						class="aside__item-title"
						href="/admin/eclients"
						@click.prevent="$router.push({ name: 'eclients' })"
					>
						<VueIcon
							:name="'Patient List'"
							:fill="'white'"
							:width="'26px'"
							:height="'26px'"
						/>
						КЛИЕНТЫ
					</a>
				</div>

				<div class="aside__item">
					<a
						class="aside__item-title"
						:class="{ active: lists.info.status }"
						href="#"
						@click.prevent="openList('info')"
					>
						<VueIcon :name="'Info'" :fill="'white'" :width="'26px'" :height="'26px'" />
						<VueIcon
							class="item-arrow"
							:name="'Arrow'"
							:fill="'white'"
							:width="'20px'"
							:height="'20px'"
							:rotate="180"
						/>
						ИНФОРМАЦИЯ
					</a>

					<div class="item-list" ref="einfo" :class="{ active: lists.info.status }">
						<a
							href="/admin/enews"
							:class="{ active: isActive('/admin/enews') }"
							@click.prevent="$router.push({ name: 'enews' })"
						>
							НОВОСТИ
						</a>

						<a
							href="/admin/evideo"
							:class="{ active: isActive('/admin/evideo') }"
							@click.prevent="$router.push({ name: 'evideo' })"
						>
							ВИДЕО
						</a>

						<a
							href="/admin/econtacts"
							:class="{ active: isActive('/admin/econtacts') }"
							@click.prevent="$router.push({ name: 'econtacts' })"
						>
							КОНТАКТЫ
						</a>

						<a
							href="/admin/eabout"
							:class="{ active: isActive('/admin/eabout') }"
							@click.prevent="$router.push({ name: 'eabout' })"
						>
							О НАС
						</a>
					</div>
				</div>

				<div class="aside__item">
					<a
						class="aside__item-title"
						:class="{ active: lists.statistics.status }"
						href="#"
						@click.prevent="openList('statistics')"
					>
						<VueIcon :name="'Monitoring'" :fill="'white'" :width="'26px'" :height="'26px'" />
						<VueIcon
							class="item-arrow"
							:name="'Arrow'"
							:fill="'white'"
							:width="'20px'"
							:height="'20px'"
							:rotate="180"
						/>
						СТАТИСТИКА
					</a>

					<div class="item-list" ref="einfo" :class="{ active: lists.statistics.status }">
						<a
							href="/admin/statistics/graphs"
							:class="{ active: isActive('/admin/statistics/graphs') }"
							@click.prevent="$router.push({ name: 'statistics-graph' })"
						>
							ГРАФИКИ
						</a>

						<a
							href="/admin/statistics/list"
							:class="{ active: isActive('/admin/statistics/list') }"
							@click.prevent="$router.push({ name: 'statistics-list' })"
						>
							СПИСОК
						</a>
					</div>
				</div>
			</template>

			<template v-if="isCreator">
				<div class="aside__item" :class="{ active: isActive('/admin/users') }">
					<a
						class="aside__item-title"
						href="/admin/users"
						@click.prevent="$router.push({ name: 'users' })"
					>
						<VueIcon :name="'Group'" :fill="'white'" :width="'26px'" :height="'26px'" />
						ПОЛЬЗОВАТЕЛИ
					</a>
				</div>
			</template>
		</div>

		<div class="aside__footer">
			<button class="aside__close" @click="openModal('modalExit', 'Выход', 'default')">
				Выйти
			</button>
		</div>
	</aside>
</template>

<script>
import axios from "axios";

export default {
	data() {
		return {
			/* Загрузка */
			loading: {
				sections: {
					aside: true,
				},
			},

			/* Модальное окно */
			modalExit: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Ссылки */
			lists: {
				specialists: {
					root: "/admin/especialists",
					status: false,
				},

				prices: {
					root: "/admin/eprices",
					status: false,
				},

				info: {
					root: ["/admin/enews", "/admin/evideo", "/admin/econtacts", "/admin/eabout"],
					status: false,
				},

				statistics: {
					root: "/admin/statistics",
					status: false,
				},
			},
		};
	},
	computed: {
		isCreator() {
			return this.$store.getters.userRights === "creator";
		},

		isAdmin() {
			return this.$store.getters.userRights === "admin";
		},
	},
	methods: {
		/* Активная ссылка */
		isActive(route) {
			return this.$route.path === route;
		},

		/* Открыть список специалистов */
		openList(page) {
			this.lists[page].status = !this.lists[page].status;

			for (let key in this.lists) {
				if (key != page) {
					this.lists[key].status = false;
				}
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},
	},
	mounted() {
		for (let key in this.lists) {
			if (Array.isArray(this.lists[key].root)) {
				for (let i = 0; i < this.lists[key].root.length; i++) {
					if (window.location.pathname.includes(this.lists[key].root[i])) {
						this.lists[key].status = true;
					}
				}

				continue;
			}

			if (window.location.pathname.includes(this.lists[key].root)) {
				this.lists[key].status = true;
			}
		}
	},
};
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

.aside__body {
	padding: 20px;
	display: flex;
	flex-direction: column;
	gap: 5px;

	max-height: 100%;
	overflow-y: auto;
}

aside.active {
	padding-top: 60px;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0;
}

/* Скролбар блока с контентом. */
.aside__body::-webkit-scrollbar {
	width: 20px;
}

.aside__body::-webkit-scrollbar-track {
	background-color: rgba(255, 255, 255, 0);
	border-radius: 10px;
	margin: 2px;
}

.aside__body::-webkit-scrollbar-thumb {
	background-color: rgba(255, 255, 255, 0.3);
	border: 6px solid var(--primary-color);
	border-radius: 10px;
}

.aside__body::-webkit-scrollbar-thumb:hover {
	background-color: rgba(255, 255, 255, 0.5);
	cursor: all-scroll;
}
/* Конец. */

.aside__item {
	position: relative;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
}

.aside__item.active > .aside__item-title {
	background-color: rgba(255, 255, 255, 0.15);
	border-radius: 10px;
}

.aside__item-title {
	display: flex;
	align-items: center;
	gap: 20px;
	padding: 15px 20px;
	height: 35px;
}

.aside__item-title > svg {
	fill: white;
}

.aside__item-title.active:hover {
	background-color: rgba(255, 255, 255, 0.3);
}

.aside__item-title.active {
	background-color: rgba(255, 255, 255, 0.15);
	border-radius: 10px 10px 0px 0px;
}

.item-arrow {
	position: absolute;
	right: 15px;
	top: 23px;
}

.item-arrow:hover + .item-list > a {
	border-radius: 0px;
	background-color: rgba(255, 255, 255, 0.15);
}

.item-list {
	display: none;
}

.item-list.active {
	display: block;
	display: flex;
	flex-direction: column;
}

.item-list > a {
	border-radius: 0px;
	background-color: rgba(255, 255, 255, 0.15);
}

.item-list > a:last-child {
	border-radius: 0px 0px 10px 10px;
}

.item-list > a:is(.active, :hover) {
	background-color: rgba(255, 255, 255, 0.3);
}

.aside__footer {
	padding: 20px;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.aside__footer button {
	cursor: pointer;
}

.aside__close {
	padding: 18px 0px;
	border: 0px;
	border-radius: 10px;

	text-align: center;
	font-size: 20px;
	text-decoration: none;

	color: white;
	background-color: rgba(0, 0, 0, 0.15);

	transition: all 0.15s ease-in-out;
}

.aside__close:hover {
	background-color: rgba(0, 0, 0, 0.3);
}

a {
	padding: 18px 0px;
	border-radius: 10px;
	outline: white;

	text-align: center;
	font-size: 20px;
	text-decoration: none;

	color: white;

	transition: hover 0.15s ease-in-out;
}

a:active {
	background-color: rgba(255, 255, 255, 0.15);
}

a:hover {
	background-color: rgba(255, 255, 255, 0.15);
}

@keyframes fade {
	0% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}

@keyframes shine {
	to {
		background-position-x: -200%;
	}
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
