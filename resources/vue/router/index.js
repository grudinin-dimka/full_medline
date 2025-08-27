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
					component: () => import("../views/main/home/MainHome.vue"),
				},
				{
					path: "about",
					name: "about",
					meta: { title: "О нас" },
					component: () => import("../views/main/about/MainAbout.vue"),
				},
				{
					path: "specialists",
					name: "specialists",
					meta: { title: "Специалисты" },
					component: () => import("../views/main/specialists/MainSpecialists.vue"),
					children: [
						{
							path: "",
							name: "specialists-all",
							component: () => import("../views/main/specialists/MainSpecialistsAll.vue"),
						},
						{
							path: ":name",
							name: "specialists-profile",
							component: () => import("../views/main/specialists/MainSpecialistProfile.vue"),
						},
					],
				},
				{
					path: "prices",
					name: "prices",
					meta: { title: "Цены" },
					redirect: { name: "prices-all" },
					component: () => import("../views/main/prices/MainPrices.vue"),
					children: [
						{
							path: "",
							name: "prices-all",
							meta: { title: "Цены" },
							component: () => import("../views/main/prices/MainPricesAll.vue"),
						},
						{
							path: "clinics",
							name: "prices-clinics",
							meta: { title: "Клиники" },
							component: () => import("../views/main/prices/clinics/MainPricesClinics.vue"),
							redirect: { name: "prices-clinics-all" },
							children: [
								{
									path: "",
									name: "prices-clinics-all",
									component: () =>
										import("../views/main/prices/clinics/MainPricesClinicsAll.vue"),
								},
								{
									path: ":city/:street/:house",
									name: "prices-clinics-template",
									component: () =>
										import("../views/main/prices/clinics/MainPricesClinicsTemplate.vue"),
								},
							],
						},
						{
							path: "travels",
							name: "prices-travels",
							component: () => import("../views/main/travels/MainTravels.vue"),
						},
					],
				},
				// NOTE: Заблокировать страницу "Пластика"
				{
					path: "plastic",
					name: "plastic",
					meta: { title: "Пластическая хирургия" },
					component: () => import("../views/main/plastic/MainPlastic.vue"),
				},
				// NOTE: Заблокировать страницу "Путевки"
				{
					path: "travels",
					name: "travels",
					meta: { title: "Путевки" },
					component: () => import("../views/main/travels/MainTravels.vue"),
					redirect: { name: "travels-all" },
					children: [
						{
							path: "",
							name: "travels-all",
							component: () => import("../views/main/travels/MainTravelsAll.vue"),
						},
						{
							path: ":name",
							name: "travels-once",
							component: () => import("../views/main/travels/MainTravelsOnce.vue"),
						},
					],
				},
				{
					path: "vacancies",
					name: "vacancies",
					meta: { title: "Вакансии" },
					component: () => import("../views/main/vacancies/MainVacancies.vue"),
					redirect: { name: "vacancies-all" },
					children: [
						{
							path: "",
							name: "vacancies-all",
							component: () => import("../views/main/vacancies/MainVacanciesAll.vue"),
						},
					],
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
				{
					path: "news",
					name: "news",
					meta: { title: "Новости" },
					redirect: { name: "news-all" },
					component: () => import("../views/main/news/MainNews.vue"),
					children: [
						{
							path: "",
							name: "news-all",
							component: () => import("../views/main/news/MainNewsAll.vue"),
						},
						{
							path: ":date/:time",
							name: "news-once",
							component: () => import("../views/main/news/MainNewsOnce.vue"),
						},
					],
				},
				{
					path: "videos",
					name: "videos",
					meta: { title: "Видео" },
					redirect: { name: "videos-all" },
					component: () => import("../views/main/video/MainVideoAll.vue"),
					children: [
						{
							path: "",
							name: "videos-all",
							component: () => import("../views/main/video/MainVideoAll.vue"),
						},
					],
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
					redirect: { name: "statistics-graph" },
					children: [
						{
							path: "graphs",
							name: "statistics-graph",
							component: () => import("../views/admin/statistics/AdminStatisticsGraph.vue"),
						},
						{
							path: "list",
							name: "statistics-list",
							component: () => import("../views/admin/statistics/AdminStatisticsList.vue"),
						},
					],
				},
				{
					path: "ehome",
					name: "ehome",
					meta: { title: "(e) Главная" },
					component: () => import("../views/admin/home/AdminHome.vue"),
					redirect: { name: "ehome-all" },
					children: [
						{
							path: "",
							name: "ehome-all",
							component: () => import("../views/admin/home/AdminHomeAll.vue"),
						},
					],
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
						{
							path: "travels",
							name: "etravels",
							component: () => import("../views/admin/travels/AdminTravels.vue"),
						},
					],
				},
				/* NOTE: Заблокировать */
				{
					path: "etravels",
					name: "etravels",
					meta: { title: "(e) Путевки" },
					component: () => import("../views/admin/travels/AdminTravels.vue"),
					redirect: { name: "etravels-all" },
					children: [
						{
							path: "",
							name: "etravels-all",
							component: () => import("../views/admin/travels/AdminTravelsAll.vue"),
						},
					],
				},
				{
					path: "evacancies",
					name: "evacancies",
					meta: { title: "(e) Вакансии" },
					component: () => import("../views/admin/vacancies/AdminVacancies.vue"),
					redirect: { name: "evacancies-all" },
					children: [
						{
							path: "",
							name: "evacancies-all",
							component: () => import("../views/admin/vacancies/AdminVacanciesAll.vue"),
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
							path: ":date/:time",
							name: "enews-once",
							component: () => import("../views/admin/news/AdminNewsOnce.vue"),
						},
					],
				},
				{
					path: "evideo",
					name: "evideo",
					meta: { title: "(e) Видео" },
					component: () => import("../views/admin/video/AdminVideo.vue"),
					redirect: { name: "evideo-all" },
					children: [
						{
							path: "",
							name: "evideo-all",
							component: () => import("../views/admin/video/AdminVideoAll.vue"),
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
				{
					path: "eclients",
					name: "eclients",
					meta: { title: "(e) Клиенты" },
					redirect: { name: "eclients-all" },
					component: () => import("../views/admin/clients/AdminClients.vue"),
					children: [
						{
							path: "",
							name: "eclients-all",
							component: () => import("../views/admin/clients/AdminClientsAll.vue"),
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
	// NOTE: Заголовок документа
	document.title = `${to.meta.title} | Медицинский центр «Медлайн»`;
	next();
});

export default router;
