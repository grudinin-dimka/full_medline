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
			name: "admin",
			component: Admin,
			children: [
				{
					path: "",
					name: "admin-default",
					redirect: { name: "ehome" },
				},
				{
					path: "ehome",
					name: "ehome",
					component: () => import("../views/admin/AdminHome.vue"),
				},
				{
					path: "eabout",
					name: "eabout",
					component: () => import("../views/admin/AdminAboutUs.vue"),
				},
				{
					path: "especialists",
					name: "especialists",
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
					component: () => import("../views/admin/AdminPrice.vue"),
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

export default router;
