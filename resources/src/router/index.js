import { createRouter, createWebHistory } from "vue-router";
import Main from "../components/Main.vue";
import Login from "../components/Login.vue";
import Admin from "../components/Admin.vue";
import NotFound from "../components/NotFound.vue";

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
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
					component: () => import("../views/main/MainHome.vue"),
				},
				{
					path: "about",
					name: "about",
					component: () => import("../views/main/MainAbout.vue"),
				},
				{
					path: "specialists",
					name: "specialist",
					component: () => import("../views/main/MainSpecialists.vue"),
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
					component: () => import("../views/main/MainPrices.vue"),
				},
				{
					path: "directions",
					name: "directions",
					component: () => import("../views/main/MainDirection.vue"),
				},
				{
					path: "diagnostics",
					name: "diagnostics",
					component: () => import("../views/main/MainDiagnostics.vue"),
				},
				{
					path: "licenses",
					name: "licenses",
					component: () => import("../views/main/MainLicense.vue"),
				},
				{
					path: "videos",
					name: "videos",
					component: () => import("../views/main/MainVideos.vue"),
				},
				{
					path: "contacts",
					name: "contacts",
					component: () => import("../views/main/MainContacts.vue"),
				},
			],
		},
		// Роутер для логина
		{
			path: "/login",
			name: "Login",
			component: Login,
		},
		// Роутер для админки
		{
			path: "/admin",
			name: "Admin",
			component: Admin,
			children: [
				{
					path: "ehome",
					name: "ehome",
					component: () => import("../views/admin/AdminHome.vue"),
				},
				{
					path: "eabout",
					name: "about-us",
					component: () => import("../views/admin/AdminAboutUs.vue"),
				},
				{
					path: "especialists",
					name: "especialists",
					component: () => import("../views/admin/specialists/AdminSpecialists.vue"),
					children: [
						{
							path: "",
							name: "especialist-all",
							component: () => import("../views/admin/specialists/AdminSpecialistsAll.vue"),
						},
						{
							path: ":id",
							name: "especialist-profile",
							component: () => import("../views/admin/specialists/AdminSpecialistsProfile.vue"),
						},
					],
				},
				{
					path: "eprice",
					name: "eprice",
					component: () => import("../views/admin/AdminPrice.vue"),
				},
			],
		},
		// Роутер для не найденных страниц
		{
			path: "/:pathMatch(.*)*",
			name: "NotFound",
			component: NotFound,
		},
	],
});

export default router;
