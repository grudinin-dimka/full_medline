<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<VueModal ref="modalPrint" :settings="modalPrint">
		<template #title>
			{{ modalPrint.values.title }}
		</template>

		<template #body>
			<VueFieldset class="controls" :count="2" :gap="'10px'">
				<template #legend> НАСТРОЙКИ ПЕЧАТИ </template>

				<template #inputs>
					<VueNumber v-model.trim="fontSize" :min="10" :max="30" :placeholder="'Введите размер'">
						<template #label> РАЗМЕР ШРИФТА </template>
					</VueNumber>

					<VueSelector
						v-model.trim="fontFamily"
						:list="[
							{ value: 'Times New Roman', label: 'Times New Roman' },
							{ value: 'Arial', label: 'Arial' },
							{ value: 'Verdana', label: 'Verdana' },
							{ value: 'Tahoma', label: 'Tahoma' },
							{ value: 'Rubik', label: 'Rubik' },
						]"
						:is-clear="false"
						:is-search="false"
						:placeholder="'Выберите шрифт'"
					>
						<template #label> ВЫБОР ШРИФТА </template>
					</VueSelector>

					<VueNumber v-model.trim="marginBottom" :min="10" :max="30" :placeholder="'Введите отступ'">
						<template #label> ВЫСОТА ОТСТУПА </template>
					</VueNumber>

					<VueNumber v-model.trim="lineHeight" :min="10" :max="30" :placeholder="'Введите высоту'">
						<template #label> ВЫСОТА СТРОК </template>
					</VueNumber>
				</template>
			</VueFieldset>

			<VuePrint
				v-model="currentAppointment.data.priem.value"
				:settings="{
					width: '750px',
					styles: {
						'*': {
							fontSize: `${fontSize}px`,
							marginBottom: `${marginBottom}px`,
							lineHeight: `${lineHeight}px`,
							fontFamily: `${fontFamily}`,
						},
					},
				}"
			/>
		</template>

		<template #footer>
			<VueButton :look="'inverse'" @click="resetPrintSettings">
				<VueIcon :name="'History'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сбросить
			</VueButton>

			<VueButton @click="printWindow">
				<VueIcon :name="'Print'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Печать
			</VueButton>
		</template>
	</VueModal>

	<div class="filters">
		<div class="filters__item">
			<VueValues v-model="filters.text" :type="'search'" :placeholder="'Введите текст'" />
		</div>

		<div class="filters__item">
			<VueSelector
				v-model="filters.naim"
				:placeholder="'Выберите заголовок'"
				:list="getAppointmentsNaims"
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
					<template #name> {{ item.naim }} </template>

					<template #buttons>
						<VueAccordeonButton @click="openModalPrint(item)">
							<VueIcon :name="'Print'" :fill="'black'" :width="'24px'" :height="'24px'" />
						</VueAccordeonButton>
					</template>

					<template #body>
						<VueAccordeonBlock>
							<div class="travels__once-description-block">
								<VuePrint
									v-model="item.priem"
									:settings="{
										width: '100%',
										styles: {
											'*': {
												fontSize: `${fontSize}px`,
												marginBottom: `${marginBottom}px`,
												lineHeight: `${lineHeight}px`,
											},
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
				text: "",
				naim: "",
			},

			/* Модальное окно */
			modalPrint: {
				thin: false,
				clamped: true,
				touch: true,
				fullscreen: false,
				print: true,
				values: {
					title: "",
					look: "default",
				},
			},

			fontSize: 16,
			marginBottom: 10,
			lineHeight: 20,
			fontFamily: "Times New Roman",

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
					date_pr: {
						value: "",
						edited: false,
					},
					naim: {
						value: "",
						edited: false,
					},
					uid: {
						value: "",
						edited: false,
					},
					"1c_id": {
						value: "",
						edited: false,
					},
					"pr_an": {
						value: "",
						edited: false,
					},
					priem: {
						value: "",
						edited: false,
					},
				},
			},

			appointments: [
				{
					id: 1,
					date_pr: "2025-02-10",
					naim: "Прием врача по ОМС",
					uid: "000150606",
					"1c_id": 37739,
					pr_an: 0,
					priem: `
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
						</div>`,
				},
				{
					id: 2,
					date_pr: "2025-02-10",
					naim: "Приём врача терапевта первичный",
					uid: "000150606",
					"1c_id": 37739,
					pr_an: 0,
					priem: `
						<style media="print"> .bit-medicalHeader {display:none;}</style><div class="bit-medicalHeader" align="left"><em><font size="2" color="#888888"><div style="overflow:hidden"><div style="float:left">13.09.2023 12:54 Осмотр терапевт</div><div style="float:right"></div></div></font></em></div><header></header><div class="FORMAT" style="text-align: center; font-family: &quot;Times New Roman&quot;"></div><div class="FORMAT" style="text-align: left"><b><div style="text-align: center;"><span style="text-align: left;">Прием Врача-терапевта Гладырев А.Р</span><b>.</b></div></b><p></p><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><p><b>Дата&nbsp;осмотра:&nbsp;13.09.2023,&nbsp;пациент: Филиппенок&nbsp;Данил&nbsp;Сергеевич,&nbsp;дата рождения: 19 марта 2002&nbsp;г.&nbsp;возраст:</b>&nbsp;<span>21 год&nbsp;</span></p><div style="text-align: left;"><b style="text-align: center;">Жалобы:&nbsp;</b>нет&nbsp;</div><div style="text-align: left;"><br></div><div style="text-align: left;"><b>Ан. болезни:&nbsp;</b>Иногда болеет с обострением ОХ. Обострения 1-2 раза в год.</div><div style="text-align: left;"><br></div><p style="text-align: left; margin-top:0; margin-bottom:0"><b>Ан. жизни: Аллергии на ЛС:&nbsp;</b>отрицает<b>; Геп B, C, ВИЧ:&nbsp;</b>отрицает<b>; ХНИЗ:&nbsp;</b>отрицает<b>; Операции:&nbsp;</b>отрицает<b>.</b></p><p style="text-align: left; margin-top:0; margin-bottom:0"><b>Состояние:</b>&nbsp;удовл.;<b> Кожн. покровы:</b>&nbsp;бл. розовые;<b> Дыхание:</b>&nbsp;везик;<b> Хрипы легких:</b>&nbsp;отсутств;<b>&nbsp;</b></p><p style="text-align: left; margin-top:0; margin-bottom:0"><b>Тоны сердца:</b>&nbsp;ясные;<b> Ритмичность тонов:</b>&nbsp;ритмичные;<b> Живот:</b> безболезн.&nbsp;</p><p style="text-align: left; margin-top:0; margin-bottom:0"><b>St. Localis:&nbsp;</b></p><p style="text-align: left; margin-top:0; margin-bottom:0"><span class="Apple-tab-span" style="font-weight: bold; white-space: pre;">	</span><b>АД:&nbsp;</b>120/80;<span class="Apple-tab-span" style="font-weight: bold; white-space: pre;">	</span><b>ЧСС:</b>&nbsp;76;<span class="Apple-tab-span" style="font-weight: bold; white-space: pre;">	</span><b>ЧДД:</b>&nbsp;16;<span class="Apple-tab-span" style="white-space:pre">	</span><b>SpO2:&nbsp;</b>98.</p><p style="text-align: left; margin-top:0; margin-bottom:0"><b>Предв. DS:</b>&nbsp;Распр ОХ, ремиссия</p><p style="text-align: left; margin-top:0; margin-bottom:0"><b>Назначено:</b></p><p style="text-align: left; margin-top:0; margin-bottom:0">- Планово санаторно-курортное лечение (заполнена форма 070/у)<b style="text-align: center;"><span class="Apple-tab-span" style="white-space:pre">	</span></b></p><p style="text-align: left; margin-top:0; margin-bottom:0"><br></p><div style="width: 19cm;"><div class="FORMAT"><b>Диагноз:&nbsp;</b></div></div><div style="width: 19cm;"><div class="FORMAT"><div class="FORMAT"><div class="FORMAT">Диагноз: [M42.1] Остеохондроз позвоночника у взрослых<br></div></div></div></div><p style="margin-top: 0px; margin-bottom: 0px;"></p><div style="width: 19cm;"><div class="FORMAT"><div class="FORMAT"><div class="FORMAT"></div></div></div></div><div style="caret-color: rgb(0, 0, 0); color: rgb(0, 0, 0); font-family: &quot;Times New Roman&quot;; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; word-spacing: 0px; -webkit-text-stroke-width: 0px; width: 19cm;"><div class="FORMAT"><div class="FORMAT"><div class="FORMAT"><br></div></div></div></div><strong><div style="text-align: right;"><strong>Врач___________________________</strong>(Гладырев&nbsp;Александр&nbsp;Русланович)</div></strong></blockquote><p></p><div style="text-align: center;"></div><span class="Apple-tab-span" style="white-space:pre"><span></span></span>

						<p style="margin-top: 0; margin-bottom: 0"></p>
						<p style="margin-top: 0; margin-bottom: 0"></p><p></p><strong><div style="text-align: left;"><br></div><div style="text-align: left;"><span class="Apple-tab-span" style="white-space: pre;"><span><hr></span></span><div style="text-align: center;"><div class="FORMAT" style="font-weight: normal;"><span><img id="42bc06f6-c1d0-44b6-9ece-4b1594ab6e9d"></span><br></div><div class="FORMAT" style="font-weight: normal; text-align: left;"><p class="MsoNormal" align="center" style="text-align: center;"></p></div></div><div style="text-align: center;"><strong><div style="display: inline !important;"><span><hr></span></div></strong></div><div style="text-align: center;"><strong><div style="display: inline !important;">Врач-терапевт</div></strong></div></div></strong><b><div style="text-align: center;"><b>Гладырев Александр Русланович</b></div></b><div class="FORMAT"><p>

						</p><p class="MsoNormal" align="left">
						</p></div><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><div class="FORMAT"><div class="FORMAT"><div class="FORMAT"><div class="FORMAT" style="font-family: &quot;Times New Roman&quot;; text-align: left;"><b>Дата&nbsp;осмотра:&nbsp;13.09.2023,&nbsp;пациент:&nbsp;Филиппенок&nbsp;Данил&nbsp;Сергеевич,&nbsp;дата рождения:&nbsp;19 марта 2002&nbsp;г.&nbsp;возраст:</b>&nbsp;21 год&nbsp;</div><div class="FORMAT" style="font-family: &quot;Times New Roman&quot;; text-align: left;"><b><br></b></div><div class="FORMAT" style="font-family: &quot;Times New Roman&quot;; text-align: left;"><b>Назначено:</b></div><div class="FORMAT" style="font-family: &quot;Times New Roman&quot;; text-align: left;">- Планово санаторно-курортное лечение (заполнена форма 070/у)</div></div></div></div></blockquote><div class="FORMAT"><div class="FORMAT"><div class="FORMAT"><div class="FORMAT" style="font-family: &quot;Times New Roman&quot;; text-align: left;">&nbsp;<p><br></p></div><div class="FORMAT" style="font-family: &quot;Times New Roman&quot;; text-align: left;"><span class="Apple-tab-span" style="white-space:pre">	</span></div><div class="FORMAT" style="text-align: center;"><br></div></div></div></div></div><footer class="noShow"></footer>
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
				days.add(this.appointments[i].date_pr);
			}

			let array = [];

			days.forEach((day) => {
				array.push({
					date: new Date(day).toLocaleString("ru", { day: "numeric", month: "numeric", year: "numeric" }),
					list: this.appointments.filter((item) => item.date_pr == day),
				});
			});

			/* Фильтрация: по наименованию */
			if (this.filters.text) {
				for (let i = 0; i < array.length; i++) {
					array[i].list = array[i].list.filter((item) => {
						let lowerNaim = item.naim.toLowerCase();
						let lowerPriem = item.priem.toLowerCase();

						return (
							lowerNaim.includes(this.filters.text.toLowerCase()) ||
							lowerPriem.includes(this.filters.text.toLowerCase())
						);
					});
				}
			}

			/* Фильтрация: по наименованию */
			if (this.filters.naim) {
				for (let i = 0; i < array.length; i++) {
					array[i].list = array[i].list.filter((item) => item.naim == this.filters.naim);
				}
			}

			/* Очистка пустых ячеек массива */
			array = array.filter((item) => item.list.length > 0);

			return array;
		},

		getAppointmentsNaims() {
			let list = new Set();

			for (let i = 0; i < this.appointments.length; i++) {
				list.add(this.appointments[i].naim);
			}

			let array = [];

			list.forEach((naim) => {
				array.push({
					value: naim,
					label: naim,
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

			this.openModal("modalPrint", object.naim, "default");
		},

		/* Печать */
		printWindow() {
			window.print();
		},

		/* Сброс настроек печати */
		resetPrintSettings() {
			this.fontSize = 16;
			this.marginBottom = 10;
			this.lineHeight = 20;
			this.fontFamily = "Times New Roman";
		},
	},
};
</script>

<style scoped>
.filters {
	display: grid;
	grid-template-columns: 1fr 0.5fr;
	gap: var(--default-gap);
}

.appointment {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

@media print {
	.controls {
		display: none;
	}
}
</style>
