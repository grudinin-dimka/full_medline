import { createRouter, createWebHistory } from "vue-router";

import Main from "../views/Main.vue";
import Admin from "../views/Admin.vue";
import Login from "../views/Login.vue";
import NotFound from "../views/NotFound.vue";

const router = createRouter({
	history: createWebHistory(import.meta.env.VITE_BASE_URL || "/"),
	routes: [
		// Роутер для главной страницы
		{
			path: "/",
			name: "Main",
			component: Main,
			children: [
				{
					path: "",
					name: "home",
					meta: { title: "Главная" },
					component: () => import("../views/main/MainHome.vue"),
				},
				{
					path: "about",
					name: "about",
					meta: { title: "О нас" },
					component: () => import("../views/main/about/MainAbout.vue"),
				},
				{
					path: "specialists",
					name: "specialist",
					meta: { title: "Специалисты" },
					component: () => import("../views/main/specialists/MainSpecialists.vue"),
					children: [
						{
							path: "",
							name: "all",
							component: () => import("../views/main/specialists/MainSpecialistsAll.vue"),
						},
						{
							path: ":name",
							name: "doctorname",
							component: () => import("../views/main/specialists/MainSpecialistProfile.vue"),
						},
					],
				},
				{
					path: "prices",
					name: "prices",
					meta: { title: "Цены" },
					redirect: { name: "prices-choice" },
					component: () => import("../views/main/prices/MainPrices.vue"),
					children: [
						{
							path: "",
							name: "prices-choice",
							component: () => import("../views/main/prices/MainPricesChoice.vue"),
						},
						{
							path: ":group",
							name: "prices-group",
							component: () => import("../views/main/prices/MainPricesGroup.vue"),
						},
						{
							path: ":city/:street/:house",
							name: "prices-template",
							component: () => import("../views/main/prices/MainPricesTemplate.vue"),
						},
					],
				},
				{
					path: "plastic",
					name: "plastic",
					meta: { title: "Пластическая хирургия" },
					component: () => import("../views/main/plastic/MainPlastic.vue"),
				},
				{
					path: "schedule",
					name: "schedule",
					meta: { title: "Расписание" },
					component: () => import("../views/main/shedule/MainSchedule.vue"),
					children: [
						{
							path: "",
							name: "schedule-all",
							component: () => import("../views/main/shedule/MainScheduleAll.vue"),
						},
					],
				},
				{
					path: "contacts",
					name: "contacts",
					meta: { title: "Контакты" },
					component: () => import("../views/main/contacts/MainContacts.vue"),
				},
			],
		},
		// Роутер для админки
		{
			path: "/admin",
			name: "admin",
			component: Admin,
			redirect: { name: "admin-default" },
			children: [
				{
					path: "",
					name: "admin-default",
					redirect: { name: "ehome" },
				},
				{
					path: "profile",
					name: "profile",
					meta: { title: "Профиль" },
					component: () => import("../views/admin/profile/AdminProfile.vue"),
				},
				{
					path: "users",
					name: "users",
					meta: { title: "Пользователи" },
					redirect: { name: "users-all" },
					component: () => import("../views/admin/users/AdminUsers.vue"),
					children: [
						{
							path: "",
							name: "users-all",
							component: () => import("../views/admin/users/AdminUsersAll.vue"),
						},
					],
				},
				{
					path: "statistics",
					name: "statistics",
					meta: { title: "Статистика" },
					component: () => import("../views/admin/statistics/AdminStatistics.vue"),
					redirect: { name: "statistics-load" },
					children: [
						{
							path: "",
							name: "statistics-load",
							component: () => import("../views/admin/statistics/AdminStatisticsLoad.vue"),
						},
					],
				},
				{
					path: "ehome",
					name: "ehome",
					meta: { title: "(e) Главная" },
					component: () => import("../views/admin/AdminHome.vue"),
				},
				{
					path: "eabout",
					name: "eabout",
					meta: { title: "(e) О нас" },
					component: () => import("../views/admin/about/AdminAboutUs.vue"),
				},
				{
					path: "especialists",
					name: "especialists",
					meta: { title: "(e) Специалисты" },
					component: () => import("../views/admin/specialists/AdminSpecialists.vue"),
					redirect: { name: "especialist-all" },
					children: [
						{
							path: "",
							name: "especialists-all",
							component: () => import("../views/admin/specialists/AdminSpecialistsAll.vue"),
						},
						{
							path: ":id",
							name: "especialist-profile",
							component: () =>
								import("../views/admin/specialists/AdminSpecialistsProfile.vue"),
						},
						{
							path: "specializations",
							name: "especialists-specializations",
							component: () =>
								import("../views/admin/specialists/AdminSpecialistsSpecializations.vue"),
						},
						{
							path: "clinics",
							name: "especialists-clinics",
							component: () =>
								import("../views/admin/specialists/AdminSpecialistsClinics.vue"),
						},
					],
				},
				{
					path: "eprices",
					name: "eprices",
					meta: { title: "(e) Цены" },
					redirect: { name: "eprices-all" },
					component: () => import("../views/admin/prices/AdminPrices.vue"),
					children: [
						{
							path: "",
							name: "eprices-all",
							component: () => import("../views/admin/prices/AdminPricesAll.vue"),
						},
						{
							path: "rules",
							name: "eprices-template",
							component: () => import("../views/admin/prices/AdminPricesTemplate.vue"),
						},
					],
				},
				{
					path: "econtacts",
					name: "econtacts",
					meta: { title: "(e) Контакты" },
					component: () => import("../views/admin/contacts/AdminContacts.vue"),
				},
				{
					path: "enews",
					name: "enews",
					meta: { title: "(e) Новости" },
					component: () => import("../views/admin/news/AdminNews.vue"),
					redirect: { name: "enews-all" },
					children: [
						{
							path: "",
							name: "enews-all",
							component: () => import("../views/admin/news/AdminNewsAll.vue"),
						},
						{
							path: ":id",
							name: "enews-once",
							component: () => import("../views/admin/news/AdminNewsOnce.vue"),
						},
					],
				},
				{
					path: "eschedule",
					name: "eschedule",
					meta: { title: "(e) Расписание" },
					redirect: { name: "eschedule-all" },
					component: () => import("../views/admin/schedule/AdminSchedule.vue"),
					children: [
						{
							path: "",
							name: "eschedule-all",
							component: () => import("../views/admin/schedule/AdminScheduleAll.vue"),
						},
					],
				},
			],
		},
		// Роутер для логина
		{
			path: "/rqukrdojysacarof",
			name: "login",
			meta: { title: "Вход в систему" },
			component: Login,
		},
		// Роутер для не найденных страниц
		{
			path: "/:pathMatch(.*)*",
			name: "not-found",
			meta: { title: "404" },
			component: NotFound,
		},
	],

	/* Отмотка страницы */
	scrollBehavior(to, from, savedPosition) {
		if (savedPosition) {
			return savedPosition;
		} else {
			return {
				top: 0,
				behavior: "smooth",
			};
		}
	},
});

router.beforeEach((to, from, next) => {
	document.title = `${to.meta.title} | Медицинский центр «МедЛайн»`;
	next();
});

export default router;
