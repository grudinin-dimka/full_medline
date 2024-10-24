import { createRouter, createWebHistory } from "vue-router";
import Main from "../components/Main.vue";
import Login from "../components/Login.vue";
import Admin from "../components/Admin.vue";
import NotFound from "../components/NotFound.vue";

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
							component: () => import("../views/main/specialists/MainSpecialistAll.vue"),
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
					component: () => import("../views/main/MainPrices.vue"),
				},
				{
					path: "directions",
					name: "directions",
					meta: { title: "Направления" },
					component: () => import("../views/main/MainDirection.vue"),
				},
				{
					path: "diagnostics",
					name: "diagnostics",
					meta: { title: "Дигностика" },
					component: () => import("../views/main/MainDiagnostics.vue"),
				},
				{
					path: "licenses",
					name: "licenses",
					meta: { title: "Лицензии" },
					component: () => import("../views/main/MainLicense.vue"),
				},
				{
					path: "videos",
					name: "videos",
					meta: { title: "Видео" },
					component: () => import("../views/main/MainVideos.vue"),
				},
				{
					path: "contacts",
					name: "contacts",
					meta: { title: "Контакты" },
					component: () => import("../views/main/contacts/MainContacts.vue"),
				},
			],
		},
		// Роутер для логина
		{
			path: "/login",
			name: "login",
			meta: { title: "Вход в систему" },
			component: Login,
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
						{
							path: "educations",
							name: "especialists-educations",
							component: () =>
								import("../views/admin/specialists/AdminSpecialistsEducations.vue"),
						},
						{
							path: "works",
							name: "especialists-works",
							component: () =>
								import("../views/admin/specialists/AdminSpecialistsWorks.vue"),
						},
						{
							path: "certificates",
							name: "especialists-certificates",
							component: () =>
								import("../views/admin/specialists/AdminSpecialistsCertificates.vue"),
						},
					],
				},
				{
					path: "eprice",
					name: "eprice",
					meta: { title: "(e) Цены" },
					component: () => import("../views/admin/AdminPrice.vue"),
				},
				{
					path: "econtacts",
					name: "econtacts",
					meta: { title: "(e) Контакты" },
					component: () => import("../views/admin/contacts/AdminContacts.vue"),
				},
			],
		},
		// Роутер для не найденных страниц
		{
			path: "/:pathMatch(.*)*",
			name: "not-found",
			component: NotFound,
		},
	],
});

router.beforeEach((to, from, next) => {
	document.title = `${to.meta.title}`;
	next();
});

export default router;
