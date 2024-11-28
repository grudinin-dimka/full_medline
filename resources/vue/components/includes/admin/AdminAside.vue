<template>
	<aside :class="{ active: $store.state.burger.status }">
		<div class="aside-body">
			<div class="item active">
				<a class="item-title" href="#" @click.prevent="insertPage('profile')">
					<svg width="22" height="22" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M5.775 22.65C7.05 21.675 8.475 20.9063 10.05 20.3438C11.625 19.7813 13.275 19.5 15 19.5C16.725 19.5 18.375 19.7813 19.95 20.3438C21.525 20.9063 22.95 21.675 24.225 22.65C25.1 21.625 25.7813 20.4625 26.2688 19.1625C26.7563 17.8625 27 16.475 27 15C27 11.675 25.8313 8.84375 23.4938 6.50625C21.1563 4.16875 18.325 3 15 3C11.675 3 8.84375 4.16875 6.50625 6.50625C4.16875 8.84375 3 11.675 3 15C3 16.475 3.24375 17.8625 3.73125 19.1625C4.21875 20.4625 4.9 21.625 5.775 22.65ZM15 16.5C13.525 16.5 12.2813 15.9938 11.2688 14.9813C10.2563 13.9688 9.75 12.725 9.75 11.25C9.75 9.775 10.2563 8.53125 11.2688 7.51875C12.2813 6.50625 13.525 6 15 6C16.475 6 17.7188 6.50625 18.7313 7.51875C19.7438 8.53125 20.25 9.775 20.25 11.25C20.25 12.725 19.7438 13.9688 18.7313 14.9813C17.7188 15.9938 16.475 16.5 15 16.5ZM15 30C12.925 30 10.975 29.6063 9.15 28.8188C7.325 28.0313 5.7375 26.9625 4.3875 25.6125C3.0375 24.2625 1.96875 22.675 1.18125 20.85C0.39375 19.025 0 17.075 0 15C0 12.925 0.39375 10.975 1.18125 9.15C1.96875 7.325 3.0375 5.7375 4.3875 4.3875C5.7375 3.0375 7.325 1.96875 9.15 1.18125C10.975 0.39375 12.925 0 15 0C17.075 0 19.025 0.39375 20.85 1.18125C22.675 1.96875 24.2625 3.0375 25.6125 4.3875C26.9625 5.7375 28.0313 7.325 28.8188 9.15C29.6063 10.975 30 12.925 30 15C30 17.075 29.6063 19.025 28.8188 20.85C28.0313 22.675 26.9625 24.2625 25.6125 25.6125C24.2625 26.9625 22.675 28.0313 20.85 28.8188C19.025 29.6063 17.075 30 15 30ZM15 27C16.325 27 17.575 26.8063 18.75 26.4188C19.925 26.0313 21 25.475 21.975 24.75C21 24.025 19.925 23.4688 18.75 23.0813C17.575 22.6938 16.325 22.5 15 22.5C13.675 22.5 12.425 22.6938 11.25 23.0813C10.075 23.4688 9 24.025 8.025 24.75C9 25.475 10.075 26.0313 11.25 26.4188C12.425 26.8063 13.675 27 15 27ZM15 13.5C15.65 13.5 16.1875 13.2875 16.6125 12.8625C17.0375 12.4375 17.25 11.9 17.25 11.25C17.25 10.6 17.0375 10.0625 16.6125 9.6375C16.1875 9.2125 15.65 9 15 9C14.35 9 13.8125 9.2125 13.3875 9.6375C12.9625 10.0625 12.75 10.6 12.75 11.25C12.75 11.9 12.9625 12.4375 13.3875 12.8625C13.8125 13.2875 14.35 13.5 15 13.5Z"
						/>
					</svg>
					ПРОФИЛЬ
				</a>
			</div>
			<div class="item active" v-if="$store.state.user.rights === 'creator'">
				<a class="item-title" href="#" @click.prevent="insertPage('users')">
					<svg width="26" height="20" viewBox="0 0 33 24" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M0 24V19.8C0 18.95 0.21875 18.1688 0.65625 17.4563C1.09375 16.7438 1.675 16.2 2.4 15.825C3.95 15.05 5.525 14.4688 7.125 14.0813C8.725 13.6938 10.35 13.5 12 13.5C13.65 13.5 15.275 13.6938 16.875 14.0813C18.475 14.4688 20.05 15.05 21.6 15.825C22.325 16.2 22.9062 16.7438 23.3438 17.4563C23.7813 18.1688 24 18.95 24 19.8V24H0ZM27 24V19.5C27 18.4 26.6938 17.3438 26.0813 16.3313C25.4688 15.3188 24.6 14.45 23.475 13.725C24.75 13.875 25.95 14.1313 27.075 14.4938C28.2 14.8563 29.25 15.3 30.225 15.825C31.125 16.325 31.8125 16.8813 32.2875 17.4938C32.7625 18.1063 33 18.775 33 19.5V24H27ZM12 12C10.35 12 8.9375 11.4125 7.7625 10.2375C6.5875 9.0625 6 7.65 6 6C6 4.35 6.5875 2.9375 7.7625 1.7625C8.9375 0.5875 10.35 0 12 0C13.65 0 15.0625 0.5875 16.2375 1.7625C17.4125 2.9375 18 4.35 18 6C18 7.65 17.4125 9.0625 16.2375 10.2375C15.0625 11.4125 13.65 12 12 12ZM27 6C27 7.65 26.4125 9.0625 25.2375 10.2375C24.0625 11.4125 22.65 12 21 12C20.725 12 20.375 11.9688 19.95 11.9062C19.525 11.8438 19.175 11.775 18.9 11.7C19.575 10.9 20.0938 10.0125 20.4563 9.0375C20.8188 8.0625 21 7.05 21 6C21 4.95 20.8188 3.9375 20.4563 2.9625C20.0938 1.9875 19.575 1.1 18.9 0.3C19.25 0.175 19.6 0.09375 19.95 0.05625C20.3 0.01875 20.65 0 21 0C22.65 0 24.0625 0.5875 25.2375 1.7625C26.4125 2.9375 27 4.35 27 6ZM3 21H21V19.8C21 19.525 20.9313 19.275 20.7938 19.05C20.6562 18.825 20.475 18.65 20.25 18.525C18.9 17.85 17.5375 17.3438 16.1625 17.0063C14.7875 16.6688 13.4 16.5 12 16.5C10.6 16.5 9.2125 16.6688 7.8375 17.0063C6.4625 17.3438 5.1 17.85 3.75 18.525C3.525 18.65 3.34375 18.825 3.20625 19.05C3.06875 19.275 3 19.525 3 19.8V21ZM12 9C12.825 9 13.5313 8.70625 14.1188 8.11875C14.7063 7.53125 15 6.825 15 6C15 5.175 14.7063 4.46875 14.1188 3.88125C13.5313 3.29375 12.825 3 12 3C11.175 3 10.4688 3.29375 9.88125 3.88125C9.29375 4.46875 9 5.175 9 6C9 6.825 9.29375 7.53125 9.88125 8.11875C10.4688 8.70625 11.175 9 12 9Z"
						/>
					</svg>
					ПОЛЬЗОВАТЕЛИ
				</a>
			</div>
			<div class="item active">
				<a class="item-title" href="#" @click.prevent="insertPage('ehome')">
					<svg width="20" height="23" viewBox="0 0 24 27" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M3 24H7.5V15H16.5V24H21V10.5L12 3.75L3 10.5V24ZM0 27V9L12 0L24 9V27H13.5V18H10.5V27H0Z"
						/>
					</svg>
					ГЛАВНАЯ
				</a>
			</div>
			<div class="item active">
				<a class="item-title" href="#" @click.prevent="insertPage('eabout')">
					<svg width="22" height="22" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M13.5 22.5H16.5V13.5H13.5V22.5ZM15 10.5C15.425 10.5 15.7813 10.3563 16.0688 10.0688C16.3563 9.78125 16.5 9.425 16.5 9C16.5 8.575 16.3563 8.21875 16.0688 7.93125C15.7813 7.64375 15.425 7.5 15 7.5C14.575 7.5 14.2188 7.64375 13.9313 7.93125C13.6438 8.21875 13.5 8.575 13.5 9C13.5 9.425 13.6438 9.78125 13.9313 10.0688C14.2188 10.3563 14.575 10.5 15 10.5ZM15 30C12.925 30 10.975 29.6063 9.15 28.8188C7.325 28.0313 5.7375 26.9625 4.3875 25.6125C3.0375 24.2625 1.96875 22.675 1.18125 20.85C0.39375 19.025 0 17.075 0 15C0 12.925 0.39375 10.975 1.18125 9.15C1.96875 7.325 3.0375 5.7375 4.3875 4.3875C5.7375 3.0375 7.325 1.96875 9.15 1.18125C10.975 0.39375 12.925 0 15 0C17.075 0 19.025 0.39375 20.85 1.18125C22.675 1.96875 24.2625 3.0375 25.6125 4.3875C26.9625 5.7375 28.0313 7.325 28.8188 9.15C29.6063 10.975 30 12.925 30 15C30 17.075 29.6063 19.025 28.8188 20.85C28.0313 22.675 26.9625 24.2625 25.6125 25.6125C24.2625 26.9625 22.675 28.0313 20.85 28.8188C19.025 29.6063 17.075 30 15 30ZM15 27C18.35 27 21.1875 25.8375 23.5125 23.5125C25.8375 21.1875 27 18.35 27 15C27 11.65 25.8375 8.8125 23.5125 6.4875C21.1875 4.1625 18.35 3 15 3C11.65 3 8.8125 4.1625 6.4875 6.4875C4.1625 8.8125 3 11.65 3 15C3 18.35 4.1625 21.1875 6.4875 23.5125C8.8125 25.8375 11.65 27 15 27Z"
						/>
					</svg>
					О НАС
				</a>
			</div>
			<div class="item active">
				<a
					class="item-title"
					:class="{ active: links.specialists.status }"
					href="#"
					@click.prevent="openList('specialists')"
				>
					<svg width="24" height="24" viewBox="0 0 30 27" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M15 27C14.55 27 14.1188 26.9188 13.7063 26.7563C13.2938 26.5938 12.925 26.35 12.6 26.025L2.55 15.9375C1.675 15.0625 1.03125 14.0625 0.61875 12.9375C0.20625 11.8125 0 10.6375 0 9.4125C0 6.8375 0.8375 4.625 2.5125 2.775C4.1875 0.925 6.275 0 8.775 0C9.975 0 11.1063 0.2375 12.1688 0.7125C13.2313 1.1875 14.175 1.85 15 2.7C15.8 1.85 16.7313 1.1875 17.7938 0.7125C18.8563 0.2375 19.9875 0 21.1875 0C23.6875 0 25.7813 0.925 27.4688 2.775C29.1563 4.625 30 6.825 30 9.375C30 10.6 29.7875 11.775 29.3625 12.9C28.9375 14.025 28.3 15.025 27.45 15.9L17.3625 26.025C17.0375 26.35 16.675 26.5938 16.275 26.7563C15.875 26.9188 15.45 27 15 27ZM16.5 7.5C16.75 7.5 16.9875 7.5625 17.2125 7.6875C17.4375 7.8125 17.6125 7.975 17.7375 8.175L20.2875 12H26.5125C26.6875 11.575 26.8188 11.1438 26.9063 10.7063C26.9938 10.2688 27.0375 9.825 27.0375 9.375C26.9875 7.65 26.4125 6.16875 25.3125 4.93125C24.2125 3.69375 22.8375 3.075 21.1875 3.075C20.4125 3.075 19.6688 3.225 18.9563 3.525C18.2438 3.825 17.625 4.2625 17.1 4.8375L16.0875 5.925C15.9625 6.075 15.8 6.19375 15.6 6.28125C15.4 6.36875 15.2 6.4125 15 6.4125C14.8 6.4125 14.6 6.36875 14.4 6.28125C14.2 6.19375 14.025 6.075 13.875 5.925L12.8625 4.8375C12.3375 4.2625 11.725 3.8125 11.025 3.4875C10.325 3.1625 9.575 3 8.775 3C7.125 3 5.75 3.63125 4.65 4.89375C3.55 6.15625 3 7.65 3 9.375C3 9.825 3.0375 10.2688 3.1125 10.7063C3.1875 11.1438 3.3125 11.575 3.4875 12H10.5C10.75 12 10.9875 12.0625 11.2125 12.1875C11.4375 12.3125 11.6125 12.475 11.7375 12.675L13.05 14.625L15.075 8.55C15.175 8.25 15.3563 8 15.6188 7.8C15.8813 7.6 16.175 7.5 16.5 7.5ZM16.95 12.375L14.925 18.45C14.825 18.75 14.6375 19 14.3625 19.2C14.0875 19.4 13.7875 19.5 13.4625 19.5C13.2125 19.5 12.975 19.4375 12.75 19.3125C12.525 19.1875 12.35 19.025 12.225 18.825L9.675 15H5.85L14.7375 23.8875C14.7875 23.9375 14.8313 23.9688 14.8688 23.9813C14.9063 23.9938 14.95 24 15 24C15.05 24 15.0938 23.9938 15.1313 23.9813C15.1688 23.9688 15.2125 23.9375 15.2625 23.8875L24.1125 15H19.5C19.25 15 19.0125 14.9375 18.7875 14.8125C18.5625 14.6875 18.375 14.525 18.225 14.325L16.95 12.375Z"
						/>
					</svg>
					<IconArrowWhite :width="20" :height="20" :rotate="180" class="item-arrow" />
					СПЕЦИАЛИСТЫ
				</a>
				<div class="item-list" ref="especialists" :class="{ active: links.specialists.status }">
					<a href="#" @click.prevent="insertSubPage('especialists-all')"> СПИСОК </a>
					<a href="#" @click.prevent="insertSubPage('especialists-specializations')">
						СПЕЦИАЛИЗАЦИИ
					</a>
					<a href="#" @click.prevent="insertSubPage('especialists-clinics')"> КЛИНИКИ </a>
				</div>
			</div>
			<div class="item active">
				<a
					class="item-title"
					:class="{ active: links.prices.status }"
					href="#"
					@click.prevent="openList('prices')"
				>
					<svg width="26" height="20" viewBox="0 0 33 24" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M19.5 13.5C18.25 13.5 17.1875 13.0625 16.3125 12.1875C15.4375 11.3125 15 10.25 15 9C15 7.75 15.4375 6.6875 16.3125 5.8125C17.1875 4.9375 18.25 4.5 19.5 4.5C20.75 4.5 21.8125 4.9375 22.6875 5.8125C23.5625 6.6875 24 7.75 24 9C24 10.25 23.5625 11.3125 22.6875 12.1875C21.8125 13.0625 20.75 13.5 19.5 13.5ZM9 18C8.175 18 7.46875 17.7063 6.88125 17.1188C6.29375 16.5313 6 15.825 6 15V3C6 2.175 6.29375 1.46875 6.88125 0.88125C7.46875 0.29375 8.175 0 9 0H30C30.825 0 31.5313 0.29375 32.1188 0.88125C32.7063 1.46875 33 2.175 33 3V15C33 15.825 32.7063 16.5313 32.1188 17.1188C31.5313 17.7063 30.825 18 30 18H9ZM12 15H27C27 14.175 27.2938 13.4688 27.8813 12.8813C28.4688 12.2938 29.175 12 30 12V6C29.175 6 28.4688 5.70625 27.8813 5.11875C27.2938 4.53125 27 3.825 27 3H12C12 3.825 11.7063 4.53125 11.1188 5.11875C10.5313 5.70625 9.825 6 9 6V12C9.825 12 10.5313 12.2938 11.1188 12.8813C11.7063 13.4688 12 14.175 12 15ZM28.5 24H3C2.175 24 1.46875 23.7063 0.88125 23.1188C0.29375 22.5313 0 21.825 0 21V4.5H3V21H28.5V24Z"
						/>
					</svg>
					<IconArrowWhite :width="20" :height="20" :rotate="180" class="item-arrow" />
					ЦЕНЫ
				</a>
				<div class="item-list" ref="eprices" :class="{ active: links.prices.status }">
					<a href="#" @click.prevent="insertSubPage('eprices-all')"> СПИСОК ЦЕН </a>
					<a href="#" @click.prevent="insertSubPage('eprices-rules')"> ПРАВИЛА </a>
				</div>
			</div>
			<div class="item active">
				<a class="item-title" href="#" @click.prevent="insertPage('econtacts')">
					<svg width="25" height="25" viewBox="0 0 30 33" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M3 33V30H27V33H3ZM3 3V0H27V3H3ZM15 18C16.25 18 17.3125 17.5625 18.1875 16.6875C19.0625 15.8125 19.5 14.75 19.5 13.5C19.5 12.25 19.0625 11.1875 18.1875 10.3125C17.3125 9.4375 16.25 9 15 9C13.75 9 12.6875 9.4375 11.8125 10.3125C10.9375 11.1875 10.5 12.25 10.5 13.5C10.5 14.75 10.9375 15.8125 11.8125 16.6875C12.6875 17.5625 13.75 18 15 18ZM3 28.5C2.175 28.5 1.46875 28.2063 0.88125 27.6188C0.29375 27.0313 0 26.325 0 25.5V7.5C0 6.675 0.29375 5.96875 0.88125 5.38125C1.46875 4.79375 2.175 4.5 3 4.5H27C27.825 4.5 28.5313 4.79375 29.1188 5.38125C29.7063 5.96875 30 6.675 30 7.5V25.5C30 26.325 29.7063 27.0313 29.1188 27.6188C28.5313 28.2063 27.825 28.5 27 28.5H3ZM5.625 25.5C6.75 24.1 8.1125 23 9.7125 22.2C11.3125 21.4 13.075 21 15 21C16.925 21 18.6875 21.4 20.2875 22.2C21.8875 23 23.25 24.1 24.375 25.5H27V7.5H3V25.5H5.625ZM10.05 25.5H19.95C19.225 25 18.4438 24.625 17.6063 24.375C16.7688 24.125 15.9 24 15 24C14.1 24 13.2313 24.125 12.3938 24.375C11.5563 24.625 10.775 25 10.05 25.5ZM15 15C14.575 15 14.2188 14.8563 13.9313 14.5688C13.6438 14.2813 13.5 13.925 13.5 13.5C13.5 13.075 13.6438 12.7188 13.9313 12.4313C14.2188 12.1438 14.575 12 15 12C15.425 12 15.7813 12.1438 16.0688 12.4313C16.3563 12.7188 16.5 13.075 16.5 13.5C16.5 13.925 16.3563 14.2813 16.0688 14.5688C15.7813 14.8563 15.425 15 15 15Z"
						/>
					</svg>
					КОНТАКТЫ
				</a>
			</div>
		</div>
		<div class="aside-footer">
			<button class="btn-close" @click="logoutUser">Выйти</button>
		</div>
	</aside>
</template>

<script>
import axios from "axios";

import IconArrowWhite from "../../icons/IconArrowWhite.vue";

export default {
	components: {
		axios,
		IconArrowWhite,
	},
	data() {
		return {
			loading: {
				sections: {
					aside: true,
				},
			},
			isCreator: false,
			links: {
				home: {
					name: "ehome",
					status: false,
					list: null,
				},
				about: {
					name: "about-us",
					status: false,
					list: null,
				},
				specialists: {
					name: "specialists",
					status: false,
					list: {
						all: false,
						specialization: false,
						clinic: false,
						education: false,
						works: false,
						certificates: false,
					},
				},
				prices: {
					name: "eprices",
					status: false,
					list: {
						download: false,
						rules: false,
					},
				},
				specialists: {
					name: "admin-settings",
					status: false,
					list: {
						all: false,
						specialization: false,
						clinic: false,
						education: false,
						works: false,
						certificates: false,
					},
				},
			},
		};
	},
	methods: {
		// Открыть список специалистов
		openList(page) {
			this.links[page].status = !this.links[page].status;

			for (let key in this.links) {
				if (key != page) {
					this.links[key].status = false;
				}
			}
		},
		insertPage(page) {
			for (let key in this.links) {
				this.links[key].status = false;
			}

			this.$router.push({ name: `${page}` });

			// смена статуса закрытия бургера
			this.$store.state.burger.status = false;

			// скролл к верху
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "instant",
			});
		},
		insertSubPage(subPage) {
			this.$router.push({ name: `${subPage}` });

			// смена статуса закрытия бургера
			this.$store.state.burger.status = false;

			// скролл к верху
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "instant",
			});
		},
		// Выход из аккаунта
		logoutUser() {
			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `logout`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					token: localStorage.getItem("token"),
				},
			});
			this.$store.state.burger.status = false;
			localStorage.removeItem("token");
			this.$router.push({ name: "login" });
			return;
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

aside.active {
	padding-top: 100px;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0;
}

.aside-body {
	padding: 20px;
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.aside-body > .item {
	position: relative;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
}

.aside-body > .item.active {
	opacity: 0;
	animation: show-bottom-to-top-15 0.15s ease-out forwards;
}

.aside-body > .item.load {
	background: linear-gradient(
		110deg,
		rgba(236, 236, 236, 0.3) 8%,
		rgba(245, 245, 245, 0.4) 18%,
		rgba(236, 236, 236, 0.3) 33%
	);
	background-size: 200% 100%;
	border-radius: 10px;
	animation: 1.5s shine linear infinite;
}

.aside-body > .item.active:nth-child(1) {
	animation-delay: 0s;
}

.aside-body > .item.active:nth-child(2) {
	animation-delay: 0.05s;
}

.aside-body > .item.active:nth-child(3) {
	animation-delay: 0.1s;
}

.aside-body > .item.active:nth-child(4) {
	animation-delay: 0.15s;
}

.aside-body > .item.active:nth-child(5) {
	animation: show-bottom-to-top-15 0.25s ease-out forwards;
	animation-delay: 0.2s;
}

.aside-body > .item.active:nth-child(6) {
	animation: show-bottom-to-top-15 0.4s ease-out forwards;
	animation-delay: 0.25s;
}

.aside-body > .item.active:nth-child(7) {
	animation: show-bottom-to-top-15 0.55s ease-out forwards;
	animation-delay: 0.3s;
}

.item-title {
	display: flex;
	align-items: center;
	gap: 20px;
	padding: 15px 20px;
	height: 35px;
}

.item-title > svg {
	fill: white;
}

.item-title.active:hover {
	background-color: rgba(255, 255, 255, 0.3);
}

.item-title.active {
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

.item-list > a:hover {
	background-color: rgba(255, 255, 255, 0.3);
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

a {
	padding: 18px 0px;
	border-radius: 10px;
	outline: white;

	text-align: center;
	font-size: 22px;
	text-decoration: none;

	color: white;

	transition: hover 0.15s ease-in-out;
}

a:active {
	background-color: rgba(255, 255, 255, 0.15);
}

aside a:hover {
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
