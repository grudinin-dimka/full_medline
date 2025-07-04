<template>
	<VueModal ref="modalExit" :settings="modalExit">
		<template #title>{{ modalExit.values.title }}</template>
		<template #body>Вы уверены, что хотите выйти из системы?</template>
		<template #footer>
			<ButtonDefault
				:wide="false"
				:disabled="$store.getters.getLogoutStatus"
				@click="$store.dispatch('logout')"
			>
				Выход
			</ButtonDefault>
		</template>
	</VueModal>

	<aside :class="{ active: $store.getters.burgerAdminStatus }">
		<div class="aside__body">
			<div class="aside__item" :class="{ active: isActive('/admin/profile') }">
				<a class="aside__item-title" href="#" @click.prevent="insertPage(links.profile)">
					<Icon :name="'profile'" :fill="'white'" :width="'22px'" :height="'22px'" />
					ПРОФИЛЬ
				</a>
			</div>

			<template
				v-if="$store.getters.userRights === 'admin' || $store.getters.userRights === 'creator'"
			>
				<div class="aside__item" :class="{ active: isActive('/admin/ehome') }">
					<a class="aside__item-title" href="#" @click.prevent="insertPage(links.home)">
						<Icon :name="'home'" :fill="'white'" :width="'22px'" :height="'22px'" />
						ГЛАВНАЯ
					</a>
				</div>

				<div class="aside__item">
					<a
						class="aside__item-title"
						:class="{ active: links.specialists.status }"
						href="#"
						@click.prevent="openList('specialists')"
					>
						<Icon :name="'specialists'" :fill="'white'" :width="'24px'" :height="'24px'" />

						<Icon
							class="item-arrow"
							:name="'arrow'"
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
						:class="{ active: links.specialists.status }"
					>
						<a
							href="#"
							:class="{ active: isActive('/admin/especialists') }"
							@click.prevent="insertPage(links.specialists, links.specialists.list.all)"
						>
							СПИСОК
						</a>
						<a
							href="#"
							:class="{ active: isActive('/admin/especialists/specializations') }"
							@click.prevent="
								insertPage(links.specialists, links.specialists.list.specialization)
							"
						>
							СПЕЦИАЛИЗАЦИИ
						</a>
						<a
							href="#"
							:class="{ active: isActive('/admin/especialists/clinics') }"
							@click.prevent="insertPage(links.specialists, links.specialists.list.clinic)"
						>
							КЛИНИКИ
						</a>
					</div>
				</div>

				<div class="aside__item">
					<a
						class="aside__item-title"
						:class="{ active: links.prices.status }"
						href="#"
						@click.prevent="openList('prices')"
					>
						<Icon :name="'prices'" :fill="'white'" :width="'26px'" :height="'26px'" />

						<Icon
							class="item-arrow"
							:name="'arrow'"
							:fill="'white'"
							:width="'20px'"
							:height="'20px'"
							:rotate="180"
						/>
						ЦЕНЫ
					</a>
					<div class="item-list" ref="eprices" :class="{ active: links.prices.status }">
						<a
							href="#"
							:class="{ active: isActive('/admin/eprices') }"
							@click.prevent="insertPage(links.prices, links.prices.list.all)"
						>
							СПИСОК ЦЕН
						</a>
						<a
							href="#"
							:class="{ active: isActive('/admin/eprices/rules') }"
							@click.prevent="insertPage(links.prices, links.prices.list.template)"
						>
							ШАБЛОН
						</a>
					</div>
				</div>

				<div class="aside__item" :class="{ active: isActive('/admin/eschedule') }">
					<a class="aside__item-title" href="#" @click.prevent="insertPage(links.schedule)">
						<Icon :name="'schedule'" :fill="'white'" :width="'24px'" :height="'24px'" />
						РАСПИСАНИЕ
					</a>
				</div>

				<div class="aside__item" :class="{ active: isActive('/admin/eclients') }">
					<a class="aside__item-title" href="#" @click.prevent="insertPage(links.clients)">
						<Icon :name="'clients'" :fill="'white'" :width="'24px'" :height="'24px'" />
						КЛИЕНТЫ
					</a>
				</div>

				<div class="aside__item">
					<a
						class="aside__item-title"
						:class="{ active: links.info.status }"
						href="#"
						@click.prevent="openList('info')"
					>
						<Icon :name="'info'" :fill="'white'" :width="'24px'" :height="'24px'" />

						<Icon
							class="item-arrow"
							:name="'arrow'"
							:fill="'white'"
							:width="'20px'"
							:height="'20px'"
							:rotate="180"
						/>
						ИНФОРМАЦИЯ
					</a>
					<div class="item-list" ref="einfo" :class="{ active: links.info.status }">
						<a
							href="#"
							:class="{ active: isActive('/admin/enews') }"
							@click.prevent="insertPage(links.info, links.info.list.news)"
						>
							НОВОСТИ
						</a>
						<a
							href="#"
							:class="{ active: isActive('/admin/evideo') }"
							@click.prevent="insertPage(links.info, links.info.list.video)"
						>
							ВИДЕО
						</a>
						<a
							href="#"
							:class="{ active: isActive('/admin/econtacts') }"
							@click.prevent="insertPage(links.info, links.info.list.contacts)"
						>
							КОНТАКТЫ
						</a>
						<a
							href="#"
							:class="{ active: isActive('/admin/eabout') }"
							@click.prevent="insertPage(links.info, links.info.list.about)"
						>
							О НАС
						</a>
					</div>
				</div>
			</template>

			<template v-if="$store.getters.userRights === 'creator'">
				<div class="aside__item" :class="{ active: isActive('/admin/users') }">
					<a class="aside__item-title" href="#" @click.prevent="insertPage(links.users)">
						<Icon :name="'users'" :fill="'white'" :width="'26px'" :height="'26px'" />
						ПОЛЬЗОВАТЕЛИ
					</a>
				</div>

				<div class="aside__item">
					<a
						class="aside__item-title"
						:class="{ active: links.statistics.status }"
						href="#"
						@click.prevent="openList('statistics')"
					>
						<Icon :name="'stats'" :fill="'white'" :width="'30px'" :height="'30px'" />

						<Icon
							class="item-arrow"
							:name="'arrow'"
							:fill="'white'"
							:width="'20px'"
							:height="'20px'"
							:rotate="180"
						/>
						СТАТИСТИКА
					</a>
					<div class="item-list" ref="einfo" :class="{ active: links.statistics.status }">
						<a
							href="#"
							:class="{ active: isActive('/admin/statistics/graphs') }"
							@click.prevent="insertPage(links.statistics, links.statistics.list.graphs)"
						>
							ГРАФИКИ
						</a>
						<a
							href="#"
							:class="{ active: isActive('/admin/statistics/list') }"
							@click.prevent="insertPage(links.statistics, links.statistics.list.lists)"
						>
							СПИСОК
						</a>
					</div>
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
import VueModal from "../../modules/modal/VueModal.vue";
import ButtonDefault from "../../ui/admin/buttons/ButtonDefault.vue";

import Icon from "../../modules/icon/VueIcon.vue";

import axios from "axios";

export default {
	components: {
		VueModal,
		ButtonDefault,

		Icon,

		axios,
	},
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
			links: {
				profile: {
					name: "profile",
					status: false,
					list: null,
				},

				home: {
					name: "ehome",
					status: false,
					list: null,
				},

				specialists: {
					name: "especialists",
					status: false,
					list: {
						all: {
							name: "especialists-all",
							status: false,
							list: null,
						},
						specialization: {
							name: "especialists-specializations",
							status: false,
							list: null,
						},
						clinic: {
							name: "especialists-clinics",
							status: false,
							list: null,
						},
					},
				},

				prices: {
					name: "eprices",
					status: false,
					list: {
						all: {
							name: "eprices-all",
							status: false,
							list: null,
						},
						template: {
							name: "eprices-template",
							status: false,
							list: null,
						},
					},
				},

				schedule: {
					name: "eschedule",
					status: false,
					list: null,
				},

				clients: {
					name: "eclients",
					status: false,
					list: null,
				},

				info: {
					name: "einfo",
					status: false,
					list: {
						news: {
							name: "enews",
							status: false,
							list: null,
						},
						video: {
							name: "evideo",
							status: false,
							list: null,
						},
						contacts: {
							name: "econtacts",
							status: false,
							list: null,
						},
						about: {
							name: "eabout",
							status: false,
							list: null,
						},
					},
				},

				users: {
					name: "users",
					status: false,
					list: null,
				},

				statistics: {
					name: "statistics",
					status: false,
					list: {
						graphs: {
							name: "statistics-graph",
							status: false,
							list: null,
						},
						lists: {
							name: "statistics-list",
							status: false,
							list: null,
						},
					},
				},
			},
		};
	},
	methods: {
		/* Активная ссылка */
		isActive(route) {
			return this.$route.path === route;
		},

		/* Открыть список специалистов */
		openList(page) {
			this.links[page].status = !this.links[page].status;

			for (let key in this.links) {
				if (key != page) {
					this.links[key].status = false;
				}
			}
		},

		/* Переход на страницу */
		insertPage(parent, child = null) {
			if (!child) {
				for (let key in this.links) {
					this.links[key].status = false;
				}

				this.$router.push({ name: `${parent.name}` });
			} else {
				this.$router.push({ name: `${child.name}` });
			}

			// скролл к верху
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "instant",
			});

			this.$store.commit("closeBurgerAdmin");
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
