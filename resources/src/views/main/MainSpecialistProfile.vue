<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/specialists/${$route.params.id}`">{{
			$route.params.id
		}}</router-link>
	</info-bar>

	<block>
		<div class="container-doctor-profile">
			<img :src="`/storage/HKK2mLj6iM8WK2z9hkBMCI7YQ1IzBxDI8MYTp8dk.png`" />
			<div class="doctor-profile">
				<div class="doctor-profile-head">
					<div class="doctor-profile-head-title">
						<span>Иванов Иван Иванович</span>
					</div>
					<div class="doctor-profile-head-item">
						<span>Специальность:</span>
						<span>Нейрохирург.</span>
					</div>
					<div class="doctor-profile-head-item">
						<span>Сертификаты:</span>
						<span>«Нейрохирургия».</span>
					</div>
					<div class="doctor-profile-head-item">
						<span>Образование:</span>
						<span
							>Тюменский государственный медицинский университет, 2004
							г.</span
						>
					</div>
					<div class="doctor-profile-head-item">
						<span>Врачебный стаж:</span>
						<span>19 лет.</span>
					</div>
					<div class="doctor-profile-head-item">
						<span>Стоимость приёма:</span>
						<span>1400 рублей.</span>
					</div>
				</div>
				<div class="doctor-profile-body">
					<div class="doctor-profile-body-title">
						<span>Ключевые компетенции:</span>
					</div>
					<ul>
						<li>
							Дегенеративно-дистрофические поражения позвоночника
							(остеохондроз, сколиотические деформации позвоночника,
							стеноз позвоночного канала, спондилолистез, спондилоартроз, 
                     грыжи дисков, протрузии дисков, дискогенные болевые
							синдромы).
						</li>
						<li>
							Травмы позвоночника и спинного мозга и их последствия.
						</li>
						<li>
							Новообразования позвоночника, оболочек и корешков спинного мозга.
						</li>
						<li>
							Патология периферической нервной системы (радикулопатии, нейропатии, травмы нервов конечностей).
						</li>
						<li>
							Нейропатический болевой синдром различной этиологии.
						</li>
						<li>
							Последствия оперативных вмешательств на позвоночнике и спинном мозге (болезнь оперированного позвоночника).
						</li>
					</ul>
				</div>
			</div>
			<!-- User {{ $route.params.id }} -->
		</div>
	</block>
</template>

<script>
import InfoBar from "../../components/ui/main/InfoBar.vue";
import Block from "../../components/ui/main/Block.vue";

export default {
	components: {
		InfoBar,
		Block,
	},
};
</script>

<style scoped>
.container-doctor-profile {
   margin: 0px 30px;
	display: flex;
   gap: 20px;
	max-width: 900px;
}

.container-doctor-profile > img {
	width: 350px;
	border-radius: 15px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	height: 100%;
}

.doctor-profile {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.doctor-profile-head {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.doctor-profile-head-title {
	font-size: 24px;
}

.doctor-profile-head-item {
	display: grid;
	font-size: 16px;
	grid-template-columns: 200px 1fr;
}

.doctor-profile-body {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.doctor-profile-body-title {
	font-size: 20px;
}

.doctor-profile-body > ul {
   margin: 0px;
   padding-left: 20px;
}

.doctor-profile-body > ul > li:not(:last-child) {
   margin-bottom: 10px;
}

@media screen and (max-width: 780px) {
   .container-doctor-profile {
      justify-content: center;
      align-items: center;
      flex-direction: column;
   }
}
</style>
