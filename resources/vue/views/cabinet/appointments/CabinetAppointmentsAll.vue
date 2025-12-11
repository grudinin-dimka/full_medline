<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<VueModal ref="modalPrint" :settings="modalPrint">
		<template #title>
			{{ modalPrint.values.title }}
		</template>

		<template #body>
			<VuePrint
				v-model="currentAppointment.data.description.value"
				:settings="{
					width: '750px',
					styles: {
						'*': { fontSize: '14px', marginBottom: '10px', lineHeight: '20px' },
					},
				}"
			/>
		</template>

		<template #footer>
			<VueButton @click="printWindow">
				<VueIcon :name="'Print'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Печать
			</VueButton>
		</template>
	</VueModal>

	<div class="filters">
		<div class="filters__item">
			<VueValues v-model="filters.name" :type="'search'" :placeholder="'Введите текст'" />
		</div>

		<div class="filters__item">
			<VueSelector
				v-model="filters.title"
				:placeholder="'Выберите заголовок'"
				:list="getAppointmentsTitles"
				:is-clear="true"
				:is-search="true"
			/>
		</div>
	</div>

	<BlockOnce :minHeight="50" v-for="appointment in getAppointments">
		<template #title>{{ appointment.date }}</template>

		<template #body>
			<div class="appointment">
				<VueAccordeon v-for="item in appointment.list">
					<template #name> {{ item.title }} </template>

					<template #buttons>
						<VueAccordeonButton @click="openModalPrint(item)">
							<VueIcon :name="'Print'" :fill="'black'" :width="'24px'" :height="'24px'" />
						</VueAccordeonButton>
					</template>

					<template #body>
						<VueAccordeonBlock>
							<div class="travels__once-description-block">
								<VuePrint
									v-model="item.description"
									:settings="{
										width: '100%',
										styles: {
											'*': { fontSize: '14px', marginBottom: '10px', lineHeight: '15px' },
										},
									}"
								/>
							</div>
						</VueAccordeonBlock>
					</template>
				</VueAccordeon>
			</div>
		</template>
	</BlockOnce>
</template>

<script>
import VuePrint from "../../../components/modules/VuePrint.vue";

import VueAccordeon from "../../../components/modules/accordeon/VueAccordeon.vue";
import VueAccordeonBlock from "../../../components/modules/accordeon/VueAccordeonBlock.vue";
import VueAccordeonButton from "../../../components/modules/accordeon/VueAccordeonButton.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import shared from "../../../services/shared";

export default {
	components: {
		VuePrint,

		VueAccordeon,
		VueAccordeonBlock,
		VueAccordeonButton,

		BlockOnce,
	},
	data() {
		return {
			filters: {
				name: "",
				title: "",
			},

			/* Модальное окно */
			modalPrint: {
				thin: false,
				clamped: false,
				touch: true,
				fullscreen: true,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentAppointment: {
				errors: {
					date: {
						status: false,
						message: null,
					},
					title: {
						status: false,
						message: null,
					},
					description: {
						status: false,
						message: null,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					date: {
						value: "",
						edited: false,
					},
					title: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
				},
			},

			day: "2025-02-10",
			month: "2025-02",
			date: "2025-03-10",

			appointments: [
				{
					date: "2025-02-10",
					title: "Прием врача по ОМС",
					description: `
						<div class="bit-dontDisplayOnPrint" style="font-size:small;color:#888888;font-style:italic;overflow:hidden;text-align:left">
	<div style="float:left">13.11.2025 09:21 Терапевт прием Шадринск</div>
	<div style="float:right">Карибуллин Ильфат Фаатович</div>
</div>
<div class="FORMAT" style="WIDTH: 19cm">
			<div class="FORMAT" style="WIDTH: 19cm;">
				<div class="FORMAT">
					<h6>
						<div style="text-align: center;">
							<font size="2">
								<b style="background-color: white;">Осмотр врача Терапевта</b>
							</font>
						</div>
					</h6>
				</div>
			</div>
			<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
				<blockquote style="text-align: left; margin: 0px 0px 0px 40px; border: none; padding: 0px;">
					<font size="2">
						<b style="background-color: white;">
							<u>Дата:</u>&nbsp;</b>
						<span style="background-color: white; font-size: small;">13.11.2025</span>
						<span style="background-color: white;">&nbsp;&nbsp;</span>
						<u style="background-color: white;">
							<b>Пациент</b>
						</u>
						<i style="background-color: white;">&nbsp;</i>
						<span style="background-color: white;">
							<span style="font-size: small;">Филиппенок&nbsp;Данил&nbsp;Сергеевич</span>&nbsp;</span>Прием:&nbsp;
						первичный</font>
				</blockquote>
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
					<font size="2">
						<b></b>
					</font>
				</blockquote>
				<div data-bit-clientpart="false">
					<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
						<font size="2">
							<b>Жалобы пациента:&nbsp;</b>
							<span style="font-size: small;">На боли в горле,насморк,повышение Т тела до 38,5.</span>&nbsp;
							<b>Анамнез заболевания: 
								<span style="font-size: small;">Болеет 3 дня,принимает иммунокрезан,леденцы тантум верде</span>&nbsp;,</b>
							<b>
								<div style="display: inline !important;">Анамнез жизни: 
									<span style="font-size: small;">ознакомлен(а)</span>,</div>
							</b>
							<b>
								<span>
									<br></span>&nbsp;</b>
							<b style="background-color: white;">
								<span style="color: rgb(33, 37, 41);">Аллергологический анамнез:&nbsp;
									не отягощен<br></span>
							</b>
						</font>
					</blockquote>
					<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
						<font size="2">
							<font color="#212529">
								<span style="caret-color: rgb(33, 37, 41); background-color: rgb(255, 255, 255);">
									<b>Хронические заболевания:&nbsp;
										<span>
											<span style="font-weight: bold; font-size: small;">со слов пациента отсутствуют</span>&nbsp;&nbsp;</span>
									</b>
								</span>
							</font>
						</font>
					</blockquote>
					<font size="2">
						<b>
							<em>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; Общее</em>&nbsp;
							<em>состояние:</em>
						</b>&nbsp;
						<span style="font-size: small;">удовлетворительное</span>, &nbsp;
						<em>
							<b>Телосложение:</b>
						</em>&nbsp;
						<span style="font-size: small;">нормостеник</span>.</font>
					<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
						<font size="2">
							<b style="background-color: rgb(255, 255, 255);">
								<span style="color: rgb(33, 37, 41);">Объективные данные:&nbsp;</span>
							</b>
							<b style="background-color: rgb(255, 255, 255);">
								<span lang="EN-US" style="color: rgb(33, 37, 41);">SpO</span>
							</b>
							<b style="background-color: rgb(255, 255, 255);">
								<span style="color: rgb(33, 37, 41);">2-&nbsp;
									<span style="width: 25px; font-size: small;">98</span>%,&nbsp;</span>
							</b>
							<b>
								<em>Рост</em>:</b>&nbsp;
							<span style="font-size: small;">185</span>,&nbsp;
							<b>
								<em>Вес</em>:&nbsp;</b>
							<span style="font-size: small;">92</span>, 
							<em>
								<b>ИМТ:</b>
							</em>&nbsp;
							<span style="font-size: small;">26,9</span>,
							<em>
								<b>Температура тела:</b>
							</em>&nbsp;
							<span style="font-size: small;">36,6</span>,
							<b style="background-color: rgb(255, 255, 255);">
								<span style="color: rgb(33, 37, 41);">&nbsp;
									<br></span>
							</b>
						</font>
					</blockquote>
					<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
						<font size="2">
							<b>АД</b>&nbsp;
							<span style="width: 25px; font-size: small;">120</span>/&nbsp;
							<span style="width: 25px; font-size: small;">80</span>&nbsp;, &nbsp;
							<b>
								<em>ЧСС</em>:&nbsp;
								<span>
									<span style="font-size: small;">98</span>
								</span>
							</b>,
							<b style="background-color: rgb(255, 255, 255);">
								<span style="color: rgb(33, 37, 41);">&nbsp; &nbsp;</span>
							</b>
							<b>
								<em>ЧДД</em>:&nbsp;</b>
							<span style="font-size: small;">16</span>,&nbsp;
							<b>Зев:</b> 
							<span style="font-size: small;">гиперемия</span>,&nbsp;
							<b>Дыхание:</b>&nbsp;
							<span style="font-size: small;"> везикулярное, хрипов нет.</span>, 
							<span id="Заголовок_eb9e01ac-ac0a-11e2-87c6-002618dcef2c" paramtitle="Осанка">&nbsp;</span>
							<span id="Заголовок_35aeefe6-ac0a-11e2-87c6-002618dcef2c" paramtitle="Кожные покровы">
								<em>
									<b>Кожные покровы:</b>
								</em>&nbsp;
								<span style="font-size: small;">бледно-розовые, физиологической влажности, сыпи нет.</span>
							</span>
							<span id="Заголовок_36b0ed9e-ac0b-11e2-87c6-002618dcef2c" paramtitle="Подкожно-жировая клетчатка">,</span>
							<span id="Заголовок_8482af87-ac0a-11e2-87c6-002618dcef2c" paramtitle="Лимфатические узлы">
								<em>
									<b>Лимфатические узлы:&nbsp;</b>
								</em>
								<span style="font-size: small;">не увеличены</span>
							</span>&nbsp; &nbsp;
							<span id="Заголовок_7c09139a-ac0a-11e2-87c6-002618dcef2c" paramtitle="Костно-мышечная система">
								<em>
									<b>Костно-мышечная система:</b>
								</em>&nbsp;
								<span style="font-size: small;">без особенностей</span>, &nbsp;&nbsp;</span>
							<span id="Заголовок_65764f2c-ac0b-11e2-87c6-002618dcef2c" paramtitle="Суставы">
								<em>
									<b>Суставы:</b>
								</em>&nbsp;
								<span style="font-weight: bold; font-size: small;"></span>,&nbsp;
								<br></span>
						</font>
					</blockquote>
					<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
						<font size="2">
							<span paramtitle="Суставы">
								<b>
									<i>Сердечно-сосудистая система:</i>
								</b>&nbsp;
								<span style="font-size: small;">тоны сердца ясные, ритмичные</span>,&nbsp;</span>
							<b>Шумы сердца: </b>
							<span style="font-size: small;">нет</span>
							<i style="font-weight: bold;">ЖКТ: &nbsp;</i>Язык:
							<b>&nbsp;</b>
							<span style="font-weight: bold; font-size: small;">Чистый, влажный</span>
							<b>,&nbsp;&nbsp;</b>Живот: 
							<span style="font-weight: bold; font-size: small;">при пальпации мягкий, безболезненный</span>
							<b>, &nbsp; </b>Печень:
							<b>&nbsp;</b>
							<span style="font-weight: bold; font-size: small;">по краю реберной дуги, безболезненная</span>
							<b>&nbsp;</b>Стул&nbsp;:&nbsp;
							<span style="font-size: small;">ежедневный,оформленный</span>
						</font>
					</blockquote>
					<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
						<font size="2">
							<i style="font-weight: bold;">Мочевыделительная система:&nbsp;</i>Симптом поколачивания по пояснице: 
							<span style="font-weight: bold; font-size: small;">отрицательный</span>
							<b>,&nbsp;</b>Мочеиспускание:&nbsp;
							<span style="width: 25px; font-size: small;">5</span>раз в сутки,&nbsp;
							<span style="font-size: small;">безболезненное,свободное</span>
							<b>&nbsp;</b>
							<i>&nbsp;
								<span>
									<br></span>
								<span>
									<div></div>
								</span>
							</i>
						</font>
					</blockquote>
					<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
						<font size="2">
							<div>
								<b>Отеки:</b> 
								<span style="font-size: small;">нет</span>
							</div>
						</font>
					</blockquote>
				</div>
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
					<font size="2"></font>
				</blockquote>
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
					<font size="2">
						<b style="background-color: white;">
							<span style="color: rgb(33, 37, 41);">Диагноз по осмотру:</span>
						</b>
						<b>
							<span style="line-height: 16.100000381469727px; color: rgb(33, 37, 41);">
								<span style="width: 725px; height: 33px; font-size: small;">J06.9 ОРВИ,ср.ст.тяж.О. ларингит.О. синусит.</span></span>
						</b>
					</font>
				</blockquote>
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
					<font size="2">
						<b style="background-color: white;">
							<span style="color: rgb(33, 37, 41);">Основной диагноз :
								Диагноз: [J06.9] Острая инфекция верхних дыхательных путей неуточненная<br>Описание: J06.9 ОРВИ,ср.ст.тяж.О. ларинготрахеит.<br>Классификация: Предварительный, Основное заболевание<br></span>
						</b>
					</font>
				</blockquote>
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
					<font size="2">
						<b>
							<span style="line-height: 115%; color: rgb(33, 37, 41);">Обследование:&nbsp;
								<span style="font-size: small;">ОАК,ОАМ<br>мазок на коронавирус</span>
							</span>
						</b>
					</font>
				</blockquote>
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
					<font size="2">
						<b>
							<span style="line-height: 115%; color: rgb(33, 37, 41);">Назначенные препараты:
								
							</span>
						</b>
					</font>
				</blockquote>
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
					<font size="2">
						<b>
							<span>
								<div>Рекомендации: 
									<span style="font-size: small;"> Амоксициллин&nbsp; 875 мг+ Клавулановая кислота 125 мг (Амоксиклав) 1000&nbsp; мг 1 т 2 р в д-5 дн<br>Ингалипт аэрозоль 3 р в д в горло-5 дн<br>Хлоргексидин полоскание горла 4 р в д-5 дн<br>Изофра 1 д 4 р в д в нос-5 дн</span>
								</div>
							</span>
						</b>
					</font>
				</blockquote>
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
					<div class="FORMAT">
						<h5>
							<font size="2">Листок нетрудоспособности, справка: 
								<span style="font-size: small;">От л/н больной отказался. Явка 18.11.2025.</span>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</font>
						</h5>
						<h5>
							<font size="2">&nbsp;
								<em>
									<u>
										<b>Врач: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</b>
									</u>
								</em>&nbsp;
								<span style="font-size: small;">Карибуллин&nbsp;Ильфат&nbsp;Фаатович</span>
							</font>
						</h5>
					</div>
				</blockquote>
			</blockquote>
			<p>
				<span></span>
			</p>
			<p></p>
		</div>
					`,
				},
				{
					date: "2025-02-10",
					title: "Приём врача терапевта первичный",
					description: `
					<p>
						<b style="background-color: white;">
						<u>Дата:</u>&nbsp;</b>
						<span style="background-color: white; font-size: small;">13.11.2025</span>
						<span style="background-color: white;">&nbsp;&nbsp;</span>
						<u style="background-color: white;">
							<b>Пациент</b>
						</u>
						<i style="background-color: white;">&nbsp;</i>
						<span style="background-color: white;">
						<span style="font-size: small;">Филиппенок&nbsp;Данил&nbsp;Сергеевич</span>&nbsp;</span>Прием:&nbsp;первичный	
					</p>
			
					<p>
						<b>Жалобы пациента:&nbsp;</b>
						<span style="font-size: small;">На боли в горле,насморк,повышение Т тела до 38,5.</span>&nbsp;
						<b>Анамнез заболевания: 
							<span style="font-size: small;">Болеет 3 дня,принимает иммунокрезан,леденцы тантум верде</span>&nbsp;,</b>
						<b>
							<div style="display: inline !important;">Анамнез жизни: 
							<span style="font-size: small;">ознакомлен(а)</span>,</div>
						</b>
					</p>			
				`,
				},
			],
		};
	},
	computed: {
		getCurrentMonth() {
			return new Date(this.month).getMonth() + 1;
		},

		getCurrentYear() {
			return new Date(this.month).getFullYear();
		},

		getAppointments() {
			let days = new Set();

			for (let i = 0; i < this.appointments.length; i++) {
				days.add(this.appointments[i].date);
			}

			let array = [];

			days.forEach((day) => {
				array.push({
					date: new Date(day).toLocaleString("ru", { day: "numeric", month: "numeric", year: "numeric" }),
					list: this.appointments.filter((item) => item.date == day),
				});
			});

			return array;
		},

		getAppointmentsTitles() {
			let list = new Set();

			for (let i = 0; i < this.appointments.length; i++) {
				list.add(this.appointments[i].title);
			}

			let array = [];

			list.forEach((title) => {
				array.push({
					value: title,
					label: title,
				});
			});

			return array;
		},
	},
	methods: {
		changeSelectedDay(day) {
			this.day = this.month + "-" + day;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 МОДАЛЬНОЕ ОКНО                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openModalPrint(object) {
			shared.clearObjectFull(this.currentAppointment);
			shared.setData(object, this.currentAppointment);

			this.openModal("modalPrint", object.title, "default");
		},

		printWindow() {
			window.print();
		},

		sanitizeHtml(html) {
			let wrapper = document.createElement("div");
			wrapper.innerHTML = html;

			wrapper.querySelectorAll("[style]").forEach((el) => {
				el.removeAttribute("style");
			});

			return wrapper.innerHTML;
		},
	},
};
</script>

<style scoped>
.filters {
	display: grid;
	grid-template-columns: 1fr 0.5fr;
	gap: calc(var(--default-gap) / 2);
}

.appointment {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}
</style>
