<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */


/** *************************** Includes ********************************** */

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';


class homeconnect extends eqLogic {

	/** *************************** Constantes ******************************** */

	const API_AUTH_URL = "/security/oauth/authorize"; //?client_id=XXX&redirect_uri=XXX&response_type=code&scope=XXX&state=XXX
	const API_TOKEN_URL = "/security/oauth/token"; //client_id=XXX&redirect_uri=XXX&grant_type=authorization_code&code=XXX
	const API_REQUEST_URL = "/api/homeappliances";
	const API_EVENTS_URL = "/api/homeappliances/events";

    public static function appliancesList() {
        $applicances = array(
            'Oven' => __("Four", __FILE__),
            'Dishwasher' => __("Lave-vaisselle", __FILE__),
            'Washer' => __("Lave-linge", __FILE__),
            'Dryer' => __("Sèche-linge", __FILE__),
            'WasherDryer' => __("Lave-linge séchant", __FILE__),
            'FridgeFreezer' => __("Réfrigérateur combiné", __FILE__),
            'Refrigerator' => __("Réfrigérateur", __FILE__),
            'Freezer' => __("Congélateur", __FILE__),
            'WineCooler' => __("Refroidisseur à vin", __FILE__),
            'CoffeeMaker' => __("Machine à café", __FILE__),
            'Hob' => __("Table de cuisson", __FILE__),
            'Hood' => __("Hotte", __FILE__),
            'CleaningRobot' => __("Robot de nettoyage", __FILE__),
            'CookProcessor' => __("Robot cuiseur", __FILE__),
            'WarmingDrawer' => __("Tiroir chauffant", __FILE__)
        );
        return $applicances;
    }

    public static function appliancesCapabilities() {
	/**
	 * Liste toutes les clés Home connect, les valeurs, type...
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return	$KEYS		array		Tableau de toutes les clés
	 */
        $KEYS = array(
            'ConsumerProducts.CoffeeMaker.Event.BeanContainerEmpty' => array(
                'name' => __("Compartiment vide", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'CoffeeMachine'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CoffeeMaker.Event.WaterTankEmpty' => array(
                'name' => __("Réservoir d'eau vide", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'CoffeeMachine'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    )
                )
            ) ,
            'ConsumerProducts.CoffeeMaker.Event.DripTrayFull' => array(
                'name' => __("Bac de récupération plein", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'CoffeeMachine'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.Beverage.Ristretto' => array(
                'name' => __("Ristretto", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.Beverage.Espresso' => array(
                'name' => __("Espresso", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.Beverage.EspressoDoppio' => array(
                'name' => __("Double Espresso", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.Beverage.Coffee' => array(
                'name' => __("Café", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.Beverage.XLCoffee' => array(
                'name' => __("Café XL", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.Beverage.EspressoMacchiato' => array(
                'name' => __("Espresso macchiato", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.Beverage.Cappuccino' => array(
                'name' => __("Cappuccino", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.Beverage.LatteMacchiato' => array(
                'name' => __("Macchiato au lait", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.Beverage.CaffeLatte' => array(
                'name' => __("Café au lait", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.Beverage.MilkFroth' => array(
                'name' => __("Mousse de lait", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.Beverage.WarmMilk' => array(
                'name' => __("Lait chaud", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.KleinerBrauner' => array(
                'name' => __("Petit café", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.GrosserBrauner' => array(
                'name' => __("Grand café", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.Verlaengerter' => array(
                'name' => __("Rallongé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.VerlaengerterBraun' => array(
                'name' => __("Café brun rallongé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.WienerMelange' => array(
                'name' => __("Mélange viennois", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.FlatWhite' => array(
                'name' => __("Blanc pur", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.Cortado' => array(
                'name' => __("Coupé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.CafeCortado' => array(
                'name' => __("Café coupé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.CafeConLeche' => array(
                'name' => __("Cafe con leche", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.CafeAuLait' => array(
                'name' => __("Cafe Au Lait", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.Doppio' => array(
                'name' => __("Double", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.Kaapi' => array(
                'name' => __("Kaapi", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.KoffieVerkeerd' => array(
                'name' => __("Mauvais café", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.Galao' => array(
                'name' => __("Galao", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.Garoto' => array(
                'name' => __("Garoto", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.Americano' => array(
                'name' => __("American", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.RedEye' => array(
                'name' => __("RedEye", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.BlackEye' => array(
                'name' => __("BlackEye", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Program.CoffeeWorld.DeadEye' => array(
                'name' => __("DeadEye", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CoffeeMaker.Option.BeanAmount' => array(
                'name' => __("Quantité de grains", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'CoffeeMachine'
                ) ,
                'enum' => array(
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.VeryMild' => array(
                        'name' => __("Très doux", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.Mild' => array(
                        'name' => __("Doux", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.MildPlus' => array(
                        'name' => __("Doux +", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.Normal' => array(
                        'name' => __("Normal", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.NormalPlus' => array(
                        'name' => __("Normal +", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.Strong' => array(
                        'name' => __("Fort", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.StrongPlus' => array(
                        'name' => __("Fort +", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.VeryStrong' => array(
                        'name' => __("Très fort", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.VeryStrongPlus' => array(
                        'name' => __("Très fort +", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.ExtraStrong' => array(
                        'name' => __("Extrèmement fort", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.DoubleShot' => array(
                        'name' => __("Double shot", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.DoubleShotPlus' => array(
                        'name' => __("Double shot +", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.DoubleShotPlusPlus' => array(
                        'name' => __("Double shot ++", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.TripleShot' => array(
                        'name' => __("Triple shot", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.TripleShotPlus' => array(
                        'name' => __("Triple shot +", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanAmount.CoffeeGround' => array(
                        'name' => __("Café moulu", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CoffeeMaker.Option.FillQuantity' => array(
                'name' => __("Contenance", __FILE__) ,
                'action' => 'Option',
                'type' => 'Int',
                'available' => array(
                    'CoffeeMachine'
                ) ,
            ) ,
            'ConsumerProducts.CoffeeMaker.Option.CoffeeTemperature' => array(
                'name' => __("Température du café", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'CoffeeMachine'
                ) ,
                'enum' => array(
                    'ConsumerProducts.CoffeeMaker.EnumType.CoffeeTemperature.88C' => array(
                        'name' => __("88 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.CoffeeTemperature.90C' => array(
                        'name' => __("90 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.CoffeeTemperature.92C' => array(
                        'name' => __("92 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.CoffeeTemperature.94C' => array(
                        'name' => __("94 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.CoffeeTemperature.95C' => array(
                        'name' => __("95 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.CoffeeTemperature.96C' => array(
                        'name' => __("96 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.CoffeeTemperature.Normal' => array(
                        'name' => __("Normale", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.CoffeeTemperature.High' => array(
                        'name' => __("Élevée", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.CoffeeTemperature.VeryHigh' => array(
                        'name' => __("Très élevée", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CoffeeMaker.Option.BeanContainerSelection' => array(
                'name' => __("Compartiment à grains", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'CoffeeMachine'
                ) ,
                'enum' => array(
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanContainerSelection.Left' => array(
                        'name' => __("Compartiment gauche", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.BeanContainerSelection.Right' => array(
                        'name' => __("Compartiment droit", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CoffeeMaker.Option.FlowRate' => array(
                'name' => __("Débit", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'CoffeeMachine'
                ) ,
                'enum' => array(
                    'ConsumerProducts.CoffeeMaker.EnumType.FlowRate.Normal' => array(
                        'name' => __("Débit normal", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.FlowRate.Intense' => array(
                        'name' => __("Débit élevé", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CoffeeMaker.EnumType.FlowRate.IntensePlus' => array(
                        'name' => __("Débit élevé +", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CoffeeMaker.Option.MultipleBeverages' => array(
                'name' => __("Boissons multiples", __FILE__) ,
                'action' => 'Option'
            ) ,
            'ConsumerProducts.CoffeeMaker.Setting.CupWarmer' => array(
                'name' => __("Chauffe-tasse", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Boolean',
                'available' => array(
                    'CoffeeMachine'
                ) ,
            ) ,
            'ConsumerProducts.CoffeeMaker.Status.BeverageCounterCoffee' => array(
                'name' => __("Nombre de tasses de café consommées", __FILE__) ,
                'action' => 'Status',
                'type' => 'Int',
                'available' => array(
                    'CoffeeMachine'
                ) ,
            ) ,
            'ConsumerProducts.CoffeeMaker.Status.BeverageCounterPowderCoffee' => array(
                'name' => __("Nombre de tasses de café en poudre consommées", __FILE__) ,
                'action' => 'Status',
                'type' => 'Int',
                'available' => array(
                    'CoffeeMachine'
                ) ,
            ) ,
            'ConsumerProducts.CoffeeMaker.Status.BeverageCounterHotWater' => array(
                'name' => __("Quantité d'eau chaude consommée", __FILE__) ,
                'action' => 'Status',
                'type' => 'Int',
                'available' => array(
                    'CoffeeMachine'
                ) ,
            ) ,
            'ConsumerProducts.CoffeeMaker.Status.BeverageCounterHotWaterCups' => array(
                'name' => __("Nombre de tasses d'eau chaude consommées", __FILE__) ,
                'action' => 'Status',
                'type' => 'Int',
                'available' => array(
                    'CoffeeMachine'
                ) ,
            ) ,
            'ConsumerProducts.CoffeeMaker.Status.BeverageCounterHotMilk' => array(
                'name' => __("Nombre de tasses de lait chaud consommées", __FILE__) ,
                'action' => 'Status',
                'type' => 'Int',
                'available' => array(
                    'CoffeeMachine'
                ) ,
            ) ,
            'ConsumerProducts.CoffeeMaker.Status.BeverageCounterFrothyMilk' => array(
                'name' => __("Nombre de tasses de mousse de lait consommées", __FILE__) ,
                'action' => 'Status',
                'type' => 'Int',
                'available' => array(
                    'CoffeeMachine'
                ) ,
            ) ,
            'ConsumerProducts.CoffeeMaker.Status.BeverageCounterMilk' => array(
                'name' => __("Nombre de tasses de lait consommées", __FILE__) ,
                'action' => 'Status',
                'type' => 'Int',
                'available' => array(
                    'CoffeeMachine'
                ) ,
            ) ,
            'ConsumerProducts.CoffeeMaker.Status.BeverageCounterCoffeeAndMilk' => array(
                'name' => __("Nombre de tasses de café au lait consommées", __FILE__) ,
                'action' => 'Status',
                'type' => 'Int',
                'available' => array(
                    'CoffeeMachine'
                ) ,
            ) ,
            'ConsumerProducts.CoffeeMaker.Status.BeverageCounterRistrettoEspresso' => array(
                'name' => __("Nombre de tasses de ristretto consommées", __FILE__) ,
                'action' => 'Status',
                'type' => 'Int',
                'available' => array(
                    'CoffeeMachine'
                ) ,
            ) ,
            'ConsumerProducts.CleaningRobot.Option.ReferenceMapId' => array(
                'name' => __("Identifiant de carte de référence", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'CleaningRobot'
                ) ,
                'enum' => array(
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.TempMap' => array(
                        'name' => __("Carte temporaire", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.Map1' => array(
                        'name' => __("Carte 1", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.Map2' => array(
                        'name' => __("Carte 2", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.Map3' => array(
                        'name' => __("Carte 3", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CleaningRobot.Option.CleaningMode' => array(
                'name' => __("Mode de nettoyage", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'CleaningRobot'
                ) ,
                'enum' => array(
                    'ConsumerProducts.CleaningRobot.EnumType.CleaningModes.Silent' => array(
                        'name' => __("Silencieux", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.CleaningModes.Power' => array(
                        'name' => __("Puissant", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.CleaningModes.Eco' => array(
                        'name' => __("Eco", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.CleaningModes.Standard' => array(
                        'name' => __("Normal", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CleaningRobot.Option.ProcessPhase' => array(
                'name' => __("Phase de traitement", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'CleaningRobot'
                ) ,
                'enum' => array(
                    'ConsumerProducts.CleaningRobot.EnumType.ProcessPhase.MovingToTarget' => array(
                        'name' => __("Déplacement vers la cible", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.ProcessPhase.Cleaning' => array(
                        'name' => __("Nettoyage", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.ProcessPhase.SearchingBaseStation' => array(
                        'name' => __("Recherche de la station de recharge", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.ProcessPhase.MovingToHome' => array(
                        'name' => __("Déplacement vers la station de recharge", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.ProcessPhase.ChargingBreak' => array(
                        'name' => __("En charge", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.ProcessPhase.MapValidationByUser' => array(
                        'name' => __("Validation de la carte par l'utilisateur", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.ProcessPhase.Exploring' => array(
                        'name' => __("Exploration", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.ProcessPhase.Localizing' => array(
                        'name' => __("Localisation", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CleaningRobot.Setting.CurrentMap' => array(
                'name' => __("Carte actuelle", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Enumeration',
                'available' => array(
                    'CleaningRobot'
                ) ,
                'enum' => array(
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.TempMap' => array(
                        'name' => __("Carte temporaire", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CleaningRobot'
                        ) ,
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.Map1' => array(
                        'name' => __("Carte 1", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CleaningRobot'
                        ) ,
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.Map2' => array(
                        'name' => __("Carte 2", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CleaningRobot'
                        ) ,
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.Map3' => array(
                        'name' => __("Carte 3", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CleaningRobot'
                        ) ,
                    ) ,
                )
            ) ,
            'ConsumerProducts.CleaningRobot.Setting.NameOfMap1' => array(
                'name' => __("Nom de carte 1", __FILE__) ,
                'action' => 'Setting',
                'type' => 'String',
                'available' => array(
                    'CleaningRobot'
                ) ,
            ) ,
            'ConsumerProducts.CleaningRobot.Setting.NameOfMap2' => array(
                'name' => __("Nom de carte 2", __FILE__) ,
                'action' => 'Setting',
                'type' => 'String',
                'available' => array(
                    'CleaningRobot'
                ) ,
            ) ,
            'ConsumerProducts.CleaningRobot.Setting.NameOfMap3' => array(
                'name' => __("Nom de carte 3", __FILE__) ,
                'action' => 'Setting',
                'type' => 'String',
                'available' => array(
                    'CleaningRobot'
                ) ,
            ) ,
            'ConsumerProducts.CleaningRobot.Setting.NameOfMap4' => array(
                'name' => __("Nom de carte 4", __FILE__) ,
                'action' => 'Setting',
                'type' => 'String',
                'available' => array(
                    'CleaningRobot'
                ) ,
            ) ,
            'ConsumerProducts.CleaningRobot.Setting.NameOfMap5' => array(
                'name' => __("Nom de carte 5", __FILE__) ,
                'action' => 'Setting',
                'type' => 'String',
                'available' => array(
                    'CleaningRobot'
                ) ,
            ) ,
            'ConsumerProducts.CleaningRobot.Program.Cleaning.CleanAll' => array(
                'name' => __("Nettoyer tout", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CleaningRobot.Program.Cleaning.CleanMap' => array(
                'name' => __("Nettoyer la carte", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CleaningRobot.Program.Basic.GoHome' => array(
                'name' => __("Retour à la station d'accueil", __FILE__) ,
                'action' => 'Program'
            ) ,
            'ConsumerProducts.CleaningRobot.Status.LastSelectedMap' => array(
                'name' => __("Dernière carte sélectionnée", __FILE__) ,
                'action' => 'Program',
                'type' => 'Enumeration',
                'available' => array(
                    'CleaningRobot'
                ) ,
                'enum' => array(
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.TempMap' => array(
                        'name' => __("Carte temporaire", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.Map1' => array(
                        'name' => __("Carte 1", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.Map2' => array(
                        'name' => __("Carte 2", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'ConsumerProducts.CleaningRobot.EnumType.AvailableMaps.Map3' => array(
                        'name' => __("Carte 3", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CleaningRobot.Status.DustBoxInserted' => array(
                'name' => __("Boîte à poussière insérée", __FILE__) ,
                'type' => 'Boolean',
                'available' => array(
                    'CleaningRobot'
                ) ,
            ) ,
            'ConsumerProducts.CleaningRobot.Status.Lost' => array(
                'name' => __("Perdu", __FILE__) ,
                'type' => 'Boolean',
                'available' => array(
                    'CleaningRobot'
                ) ,
            ) ,
            'ConsumerProducts.CleaningRobot.Status.Lifted' => array(
                'name' => __("Soulevé", __FILE__) ,
                'type' => 'Boolean',
                'available' => array(
                    'CleaningRobot'
                ) ,
            ) ,
            'ConsumerProducts.CleaningRobot.Event.EmptyDustBoxAndCleanFilter' => array(
                'name' => __("Vider la boîte à poussière et nettoyer le filtre", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'CleaningRobot'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CleaningRobot.Event.RobotIsStuck' => array(
                'name' => __("Robot coincé", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'CleaningRobot'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'ConsumerProducts.CleaningRobot.Event.DockingStationNotFound' => array(
                'name' => __("Station de recharge introuvable", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'CleaningRobot'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'Dishcare.Dishwasher.Program.PreRinse' => array(
                'name' => __("Pré-rinçage", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Auto1' => array(
                'name' => __("Auto 35-45 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Auto2' => array(
                'name' => __("Auto 45-65 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Auto3' => array(
                'name' => __("Auto 65-75 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Eco50' => array(
                'name' => __("Eco 50 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Quick45' => array(
                'name' => __("Rapide 45 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Intensiv70' => array(
                'name' => __("Intensif 70 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Normal65' => array(
                'name' => __("Normal 65 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Glas40' => array(
                'name' => __("Verres 40 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.GlassCare' => array(
                'name' => __("Soin des verres", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.NightWash' => array(
                'name' => __("Silence 50 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Quick65' => array(
                'name' => __("Rapide 65 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Normal45' => array(
                'name' => __("Normal 45 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Intensiv45' => array(
                'name' => __("Intensif 45 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.AutoHalfLoad' => array(
                'name' => __("Auto demi-charge", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.IntensivPower' => array(
                'name' => __("Puissance intensive", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.MagicDaily' => array(
                'name' => __("Magie quotidienne", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Super60' => array(
                'name' => __("Super 60 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.Kurz60' => array(
                'name' => __("Court 60 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.ExpressSparkle65' => array(
                'name' => __("Rapide étincellant 65 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.MachineCare' => array(
                'name' => __("Soin de la machine", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.SteamFresh' => array(
                'name' => __("Rinçage et séchage hygiénique", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Program.MaximumCleaning' => array(
                'name' => __("Nettoyage complet", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Dishcare.Dishwasher.Option.IntensivZone' => array(
                'name' => __("Zone intensive", __FILE__) ,
                'action' => 'Option',
                'type' => 'Boolean',
                'available' => array(
                    'Dishwasher'
                )
            ) ,
            'Dishcare.Dishwasher.Option.BrillianceDry' => array(
                'name' => __("Brillance à sec", __FILE__) ,
                'action' => 'Option',
                'type' => 'Boolean',
                'available' => array(
                    'Dishwasher'
                )
            ) ,
            'Dishcare.Dishwasher.Option.VarioSpeedPlus' => array(
                'name' => __("VarioSpeed +", __FILE__) ,
                'action' => 'Option',
                'type' => 'Boolean',
                'available' => array(
                    'Dishwasher'
                )
            ) ,
            'Dishcare.Dishwasher.Option.SilenceOnDemand' => array(
                'name' => __("Silence à la demande", __FILE__) ,
                'action' => 'Option',
                'type' => 'Boolean',
                'available' => array(
                    'Dishwasher'
                )
            ) ,
            'Dishcare.Dishwasher.Option.HalfLoad' => array(
                'name' => __("Demi-charge", __FILE__) ,
                'action' => 'Option',
                'type' => 'Boolean',
                'available' => array(
                    'Dishwasher'
                )
            ) ,
            'Dishcare.Dishwasher.Option.ExtraDry' => array(
                'name' => __("Extra sec", __FILE__) ,
                'action' => 'Option',
                'type' => 'Boolean',
                'available' => array(
                    'Dishwasher'
                )
            ) ,
            'Dishcare.Dishwasher.Option.HygienePlus' => array(
                'name' => __("Hygiène +", __FILE__) ,
                'action' => 'Option',
                'type' => 'Boolean',
                'available' => array(
                    'Dishwasher'
                )
            ) ,
            'Cooking.Hob.Program.PowerLevelMode' => array(
                'name' => __("Mode niveau de puissance", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Hob.Option.ZoneSelector' => array(
                'name' => __("Sélecteur de zone", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'Hob'
                ) ,
                'enum' => array(
                    'Cooking.Hob.EnumType.ZoneSelector.RearLeft' => array(
                        'name' => __("Arrière gauche", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.ZoneSelector.FrontLeft' => array(
                        'name' => __("Avant gauche", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.ZoneSelector.RearRight' => array(
                        'name' => __("Arrière droite", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.ZoneSelector.FrontRight' => array(
                        'name' => __("Avant droite", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'Cooking.Hob.Option.PowerLevel' => array(
                'name' => __("Niveau de puissance", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'Hob'
                ) ,
                'enum' => array(
                    'Cooking.Hob.EnumType.PowerLevel.Off' => array(
                        'name' => __("Éteint", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.PowerLevel.10' => array(
                        'name' => __("10%", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.PowerLevel.20' => array(
                        'name' => __("20%", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.PowerLevel.30' => array(
                        'name' => __("30%", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.PowerLevel.40' => array(
                        'name' => __("40%", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.PowerLevel.50' => array(
                        'name' => __("50%", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.PowerLevel.60' => array(
                        'name' => __("60%", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.PowerLevel.70' => array(
                        'name' => __("70%", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.PowerLevel.80' => array(
                        'name' => __("80%", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hob.EnumType.PowerLevel.90' => array(
                        'name' => __("90%", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                ) ,
            ) ,
            'Cooking.Hob.Option.JoinZone' => array(
                'name' => __("Joindre une zone", __FILE__) ,
                'action' => 'Option',
                'type' => 'Boolean',
                'available' => array(
                    'Hob'
                ) ,
            ) ,
            'Cooking.Oven.Program.HeatingMode.PreHeating' => array(
                'name' => __("Préchauffage", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.HotAir' => array(
                'name' => __("Convection 3D", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.HotAirEco' => array(
                'name' => __("Chaleur tournante éco", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.HotAirGrilling' => array(
                'name' => __("Gril à convection", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.TopBottomHeating' => array(
                'name' => __("Cuisson", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.TopBottomHeatingEco' => array(
                'name' => __("Cuisson éco", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.BottomHeating' => array(
                'name' => __("Résistance de sole", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.PizzaSetting' => array(
                'name' => __("Pizza", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.SlowCook' => array(
                'name' => __("Cuisson lente", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.IntensiveHeat' => array(
                'name' => __("Chaleur intensive", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.KeepWarm' => array(
                'name' => __("Maintien au chaud", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.PreheatOvenware' => array(
                'name' => __("Préchauffer le four", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.FrozenHeatupSpecial' => array(
                'name' => __("Réchauffage produit congelé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.Desiccation' => array(
                'name' => __("Déshydratation", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.Defrost' => array(
                'name' => __("Décongélation", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.Proof' => array(
                'name' => __("Levain", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.WarmingDrawer' => array(
                'name' => __("Tiroir chauffant", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.GrillSmallArea' => array(
                'name' => __("Gril basse puissance", __FILE__) ,
                'action' => 'Program'
            ) ,
            //puissance moyenne
            'Cooking.Oven.Program.HeatingMode.GrillLargeArea' => array(
                'name' => __("Gril grande puissance", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.HeatingMode.LetRest' => array(
                'name' => __("Laisser reposer", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.PotRoastedBeef' => array(
                'name' => __("Boœuf braisé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.UnstuffedDuck' => array(
                'name' => __("Canard, non farci", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.BonelessPorkNeckJoint' => array(
                'name' => __("Cou de porc désossé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.PorkNeckJoint' => array(
                'name' => __("Cou de porc rôti", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.PommesFrites' => array(
                'name' => __("Frites", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.BonelessLegOfLambMedium' => array(
                'name' => __("Gigot d'agneau désossé, à point", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.BonelessLegOfLambWellDone' => array(
                'name' => __("Gigot d'agneau désossé, bien cuit", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.BonelessLegOfVenison' => array(
                'name' => __("Gigot de venaison désossé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.Goulash' => array(
                'name' => __("Goulash", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.PotatoGratin' => array(
                'name' => __("Gratin de pommes de terre, avec pommes de terre crues", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.WholeRabbit' => array(
                'name' => __("Lapin, entier", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.FrozenLasagne' => array(
                'name' => __("Lasagne", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.StewWithVegetables' => array(
                'name' => __("Mijoté aux légumes", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.StewWithMeat' => array(
                'name' => __("Mijoté avec de la viande", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.ChickenParts' => array(
                'name' => __("Morceaux de poulet", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.UnstuffedGoose' => array(
                'name' => __("Oie, non farcie", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.MeatLoafMadeFromFreshMincedMeat' => array(
                'name' => __("Pain de viande, avec viande hachée fraîche", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.PastaBake' => array(
                'name' => __("Pâtes au four", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.PartCookedBreadRollsOrBaguette' => array(
                'name' => __("Petits pains ou baguette, pré-cuits", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.FrozenDeepPanPizza' => array(
                'name' => __("Pizza à pâte épaisse", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.FrozenThinCrustPizza' => array(
                'name' => __("Pizza à pâte mince", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.WholeFish' => array(
                'name' => __("Poisson, entier", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.TurkeyBreast' => array(
                'name' => __("Poitrine de dinde", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.WholeBakedPotatoes' => array(
                'name' => __("Pommes de terre au four, entières", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.UnstuffedChicken' => array(
                'name' => __("Poulet, non farci", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.JointOfPorkWithCrackling' => array(
                'name' => __("Rôti de porc avec couenne croustillante", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.JointOfVealLean' => array(
                'name' => __("Rôti de veau, maigre", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.JointOfVealMarbled' => array(
                'name' => __("Rôti de veau, persillé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.BeefRoulade' => array(
                'name' => __("Roulade au bœuf", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Program.Dish.Automatic.Conv.SirloinMedium' => array(
                'name' => __("Surlonge, à point", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Cooking.Oven.Option.SetpointTemperature' => array(
                'name' => __("Température cible pour l'enceinte", __FILE__) ,
                'action' => 'Option',
                'type' => 'Double',
                'available' => array(
                    'Oven'
                ) ,
            ) ,
            'Cooking.Oven.Option.FastPreHeat' => array(
                'name' => __("Préchauffage rapide", __FILE__) ,
                'action' => 'Option',
                'type' => 'Boolean',
                'available' => array(
                    'Oven'
                ) ,
            ) ,
            'Cooking.Oven.Option.WarmingLevel' => array(
                'name' => __("Niveau de chauffe", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'WarmingDrawer'
                ),
                'enum' => array(
                    'Cooking.Oven.EnumType.WarmingLevel.Low' => array(
                        'name' => __("Bas", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Oven.EnumType.WarmingLevel.Medium' => array(
                        'name' => __("Moyen", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Oven.EnumType.WarmingLevel.High' => array(
                        'name' => __("Haut", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'Cooking.Oven.Option.CavitySelector' => array(
                'name' => __("Sélecteur de cavité", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'Oven'
                ) ,
                'enum' => array(
                    'Cooking.Oven.EnumType.CavitySelector.Main' => array(
                        'name' => __("Enceinte", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'Cooking.Oven.Setting.SabbathMode' => array(
                'name' => __("Mode Sabbat", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Boolean',
                'available' => array(
                    'Oven'
                ) ,
            ) ,
            'Cooking.Oven.Status.CurrentCavityTemperature' => array(
                'name' => __("Température actuelle", __FILE__) ,
                'Setting'
            ) ,
            'Cooking.Oven.Event.PreheatFinished' => array(
                'name' => __("Préchauffage terminé", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'Cooktop',
                    'Oven'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'Cooking.Common.Option.Hood.VentingLevel' => array(
                'name' => __("Niveau de ventilation", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'Hood'
                ) ,
                'enum' => array(
                    'Cooking.Hood.EnumType.Stage.FanOff' => array(
                        'name' => __("Ventilateur éteint", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hood.EnumType.Stage.FanStage01' => array(
                        'name' => __("Phase 1 de ventilation", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hood.EnumType.Stage.FanStage02' => array(
                        'name' => __("Phase 2 de ventilation", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hood.EnumType.Stage.FanStage03' => array(
                        'name' => __("Phase 3 de ventilation", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hood.EnumType.Stage.FanStage04' => array(
                        'name' => __("Phase 4 de ventilation", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hood.EnumType.Stage.FanStage05' => array(
                        'name' => __("Phase 5 de ventilation", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'Cooking.Common.Option.Hood.IntensiveLevel' => array(
                'name' => __("Niveau d'intensité", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'enum' => array(
                    'Cooking.Hood.EnumType.IntensiveStage.IntensiveStageOff' => array(
                        'name' => __("Phase intensive arrêtée", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hood.EnumType.IntensiveStage.IntensiveStage1' => array(
                        'name' => __("Phase intensive 1", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'Cooking.Hood.EnumType.IntensiveStage.IntensiveStage2' => array(
                        'name' => __("Phase intensive 2", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'Cooking.Common.Program.Hood.Automatic' => array(
                'name' => __("Automatique", __FILE__) ,
                'action' => 'Status'
            ) ,
            'Cooking.Common.Program.Hood.Venting' => array(
                'name' => __("Ventilation", __FILE__) ,
                'action' => 'Status'
            ) ,
            'Cooking.Common.Program.Hood.DelayedShutOff' => array(
                'name' => __("Arrêt différé", __FILE__) ,
                'action' => 'Status'
            ) ,
            'Cooking.Common.Setting.Lighting' => array(
                'name' => __("Éclairage", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Boolean',
                'available' => array(
                    'Hood'
                ) ,
            ) ,
            'Cooking.Common.Setting.LightingBrightness' => array(
                'name' => __("Intensité de l'éclairage", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Double',
                'unit' => '%',
                'constraints' => array(
                    'min' => 10,
                    'max' => 100
                ),
                'available' => array(
                    'Hood'
                ) ,
            ) ,
            'LaundryCare.Dryer.Program.Cotton' => array(
                'name' => __("Coton", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Cotton.Eco4060' => array(
                'name' => __("Coton éco 40-60 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.EasyCare.EasyCare' => array(
                'name' => __("Entretien facile", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Synthetic' => array(
                'name' => __("Synthétiques", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Mix' => array(
                'name' => __("Mélangé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Wool.Wool' => array(
                'name' => __("Laine", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.WaterProof.WaterProof' => array(
                'name' => __("Imperméabiliser", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Refresh.RefreshwoHS' => array(
                'name' => __("RefreshwoHS", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Blankets' => array(
                'name' => __("Blankets", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.BusinessShirts' => array(
                'name' => __("Chemises de travail", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.DownFeathers' => array(
                'name' => __("Plumes de duvet", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Hygiene' => array(
                'name' => __("Hygiénique", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.TimeWarm' => array(
                'name' => __("Temps chaud", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Jeans' => array(
                'name' => __("Jeans", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Outdoor' => array(
                'name' => __("Extérieur", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.SyntheticRefresh' => array(
                'name' => __("Rafraîchissement synthétiques", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Towels' => array(
                'name' => __("Serviettes", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Delicates' => array(
                'name' => __("Délicat", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Super40' => array(
                'name' => __("Super 40 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Shirts15' => array(
                'name' => __("Chemises 15°C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Pillow' => array(
                'name' => __("Oreillers", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.AntiShrink' => array(
                'name' => __("Anti-rétrécissement", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.MyTime.MyDryingTime' => array(
                'name' => __("Mon temps de séchage", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.TimeCold' => array(
                'name' => __("Temps froid", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.InBasket' => array(
                'name' => __("Dans le panier", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.TimeColdFix.TimeCold20' => array(
                'name' => __("Temps froid 20 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.TimeColdFix.TimeCold30' => array(
                'name' => __("Temps froid 30 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.TimeColdFix.TimeCold60' => array(
                'name' => __("Temps froid 40 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.TimeWarmFix.TimeWarm30' => array(
                'name' => __("Temps chaud 30 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.TimeWarmFix.TimeWarm40' => array(
                'name' => __("Temps chaud 40 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.TimeWarmFix.TimeWarm60' => array(
                'name' => __("Temps chaud 60 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Program.Dessous' => array(
                'name' => __("Dessous", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Dryer.Option.DryingTarget' => array(
                'name' => __("Degré de séchage", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'Dryer',
                    'WasherDryer'
                ),
                'enum' => array(
                    'LaundryCare.Dryer.EnumType.DryingTarget.IronDry' => array(
                        'name' => __("Prêt à repasser", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Dryer.EnumType.DryingTarget.GentleDry' => array(
                        'name' => __("Séchage doux", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Dryer.EnumType.DryingTarget.CupboardDry' => array(
                        'name' => __("Prêt à ranger", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Dryer.EnumType.DryingTarget.CupboardDryPlus' => array(
                        'name' => __("Prêt à ranger +", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                )
            ) ,
            'LaundryCare.Dryer.Option.WrinkleGuard' => array(
                'name' => __("Anti-pli", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'enum' => array(
                    'LaundryCare.Dryer.EnumType.WrinkleGuard.Min60' => array(
                        'name' => __("Anti-pli min. 60", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Dryer.EnumType.WrinkleGuard.Min120' => array(
                        'name' => __("Anti-pli min. 120", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Dryer.EnumType.WrinkleGuard.Min240' => array(
                        'name' => __("Anti-pli min. 240", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                )
            ) ,
            'LaundryCare.Dryer.Event.DryingProcessFinished' => array(
                'name' => __("Séchage terminé", __FILE__) ,
                'action' => 'Event'
            ),
            'LaundryCare.Common.Option.VarioPerfect' => array(
                'name' => __("VarioPerfect", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'enum' => array(
                    'LaundryCare.Common.EnumType.VarioPerfect.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Option'
                     ) ,
                    'LaundryCare.Common.EnumType.VarioPerfect.On' => array(
                        'name' => __("Activé", __FILE__) ,
                        'action' => 'Option'
                     ) ,
                    'LaundryCare.Commmon.EnumType.VarioPerfect.SpeedPerfect' => array(
                        'name' => __("Vario vitesse parfaite", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Commmon.EnumType.VarioPerfect.SpeedPerfect1' => array(
                        'name' => __("Vario vitesse parfaite 1", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Commmon.EnumType.VarioPerfect.SpeedPerfect2' => array(
                        'name' => __("Vario vitesse parfaite 2", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                ),
            ) ,
            'LaundryCare.Common.Option.LoadRecommendation' => array(
                'name' => __("Recommandation de charges", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.Program.Cotton' => array(
                'name' => __("Coton", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Cotton.CottonEco' => array(
                'name' => __("Coton éco", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Cotton.Eco4060' => array(
                'name' => __("Coton éco 40-60 °C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Cotton.Colour' => array(
                'name' => __("Coton couleur", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.EasyCare' => array(
                'name' => __("Entretien facile", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Mix' => array(
                'name' => __("Mélangé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.DelicatesSilk' => array(
                'name' => __("Délicat/Soie", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Wool' => array(
                'name' => __("Laine", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.DrumClean' => array(
                'name' => __("Nettoyage tambour", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Spin.SpinDrain' => array(
                'name' => __("Essorage/Vidange", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Rinse' => array(
                'name' => __("Rinçage", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Rinse.Rinse' => array(
                'name' => __("Rinçage", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Rinse.RinseSpin' => array(
                'name' => __("Rinçage/Essorage", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.WashAndDry.60' => array(
                'name' => __("Lavage&Séchage 60 min.", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.WashAndDry.90' => array(
                'name' => __("Lavage&Séchage 90 min.", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Sensitive' => array(
                'name' => __("Sensible", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Auto30' => array(
                'name' => __("Auto 30°C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Auto40' => array(
                'name' => __("Auto 40°C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Auto60' => array(
                'name' => __("Auto 60°C", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Chiffon' => array(
                'name' => __("Mousseline de soie", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Curtains' => array(
                'name' => __("Rideaux", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.DarkWash' => array(
                'name' => __("Spécial couleurs sombres", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Dessous' => array(
                'name' => __("Lingerie", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Monsoon' => array(
                'name' => __("Mousson", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Outdoor' => array(
                'name' => __("Extérieur", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.PlushToy' => array(
                'name' => __("Peluche", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.ShirtsBlouses' => array(
                'name' => __("Chemises", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.SportFitness' => array(
                'name' => __("Textiles sport", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Towels' => array(
                'name' => __("Serviettes", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.WaterProof' => array(
                'name' => __("Imperméabiliser", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.Super153045.Super1530' => array(
                'name' => __("Express 15/30 min.", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Program.MyTime' => array(
                'name' => __("Mon temps", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.Washer.Option.Temperature' => array(
                'name' => __("Température", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'Washer',
                    'WasherDryer'
                ),
                'enum' => array(
                    'LaundryCare.Washer.EnumType.Temperature.Cold' => array(
                        'name' => __("À froid", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.GC20' => array(
                        'name' => __("20 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.GC30' => array(
                        'name' => __("30 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.GC40' => array(
                        'name' => __("40 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.GC50' => array(
                        'name' => __("50 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.GC60' => array(
                        'name' => __("60 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.GC70' => array(
                        'name' => __("70 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.GC80' => array(
                        'name' => __("80 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.GC90' => array(
                        'name' => __("90 °C", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.UlCold' => array(
                        'name' => __("Froid (US/CA)", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.UlWarm' => array(
                        'name' => __("Tiède (US/CA)", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.UlHot' => array(
                        'name' => __("Chaud (US/CA)", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.UlExtraHot' => array(
                        'name' => __("Très chaud (US/CA)", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.Auto' => array(
                        'name' => __("Auto", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Temperature.Max' => array(
                        'name' => __("Max", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                )
            ) ,
            'LaundryCare.Washer.Option.SpinSpeed' => array(
                'name' => __("Vitesse d'essorage", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'available' => array(
                    'Washer',
                    'WasherDryer'
                ),
                'enum' => array(
                    'LaundryCare.Washer.EnumType.SpinSpeed.Off' => array(
                        'name' => __("Arrêt", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.RPM400' => array(
                        'name' => __("400 tr/min", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.RPM600' => array(
                        'name' => __("600 tr/min", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.RPM700' => array(
                        'name' => __("700 tr/min", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.RPM800' => array(
                        'name' => __("800 tr/min", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.RPM900' => array(
                        'name' => __("900 tr/min", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.RPM1000' => array(
                        'name' => __("1000 tr/min", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.UlLow' => array(
                        'name' => __("Basse (US/CA)", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.UlMedium' => array(
                        'name' => __("Moyenne (US/CA)", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.UlHigh' => array(
                        'name' => __("Élevée (US/CA)", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.UlOff' => array(
                        'name' => __("Arrêt (US/CA)", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.RPM1200' => array(
                        'name' => __("1200 tr/min", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.RPM1400' => array(
                        'name' => __("1400 tr/min", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.RPM1500' => array(
                        'name' => __("1500 tr/min", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.RPM1600' => array(
                        'name' => __("1600 tr/min", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.Auto' => array(
                        'name' => __("Auto", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.SpinSpeed.Max' => array(
                        'name' => __("Max", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                )
            ) ,
            'LaundryCare.Washer.EnumType.ProcessPhase.Undefined' => array(
                'name' => __("Non défini", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.EnumType.ProcessPhase.Washing' => array(
                'name' => __("Lavage", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.EnumType.ProcessPhase.Rinsing' => array(
                'name' => __("Rinçage", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.EnumType.IDosingLevel.Light' => array(
                'name' => __("Dose légère", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.EnumType.IDosingLevel.Normal' => array(
                'name' => __("Dose normale", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.EnumType.IDosingLevel.High' => array(
                'name' => __("Dose élevée", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.EnumType.WaterAndRinsePlus.Plus1' => array(
                'name' => __("+1", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.EnumType.WaterAndRinsePlus.Plus2' => array(
                'name' => __("+2", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.EnumType.WaterAndRinsePlus.Plus3' => array(
                'name' => __("+3", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.Option.RinseHold' => array(
                'name' => __("Arrêt cuve pleine", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.Option.RinsePlus' => array(
                'name' => __("Rinçage +", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'enum' => array(
                    'LaundryCare.Washer.EnumType.RinsePlus.On' => array(
                        'name' => __("Rinçage + activé", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.RinsePlus.Off' => array(
                        'name' => __("Rinçage + désactivé", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                ),
            ) ,
            'LaundryCare.Washer.Option.RinsePlus1' => array(
                'name' => __("Rinçage plus", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'enum' => array(
                    'LaundryCare.Washer.EnumType.RinsePlus1.On' => array(
                        'name' => __("Rinçage + activé", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.RinsePlus1.Off' => array(
                        'name' => __("Rinçage + désactivé", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                ),
            ) ,
            'LaundryCare.Washer.Option.SilentWash' => array(
                'name' => __("Lavage silencieux", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.Option.Soak' => array(
                'name' => __("Faire tremper", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.Option.SpeedPerfect' => array(
                'name' => __("Vitesse parfaite", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.Option.LessIroning' => array(
                'name' => __("Moins de repassage", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.Option.WaterPlus' => array(
                'name' => __("Eau +", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.Option.Prewash' => array(
                'name' => __("Prélavage", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.Option.Stains' => array(
                'name' => __("Vitesse d'essorage", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'enum' => array(
                    'LaundryCare.Washer.EnumType.Stains.On' => array(
                        'name' => __("Anti-tâche activé", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.Stains.Off' => array(
                        'name' => __("Anti-tâche désactivé", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                ),
            ) ,
            'LaundryCare.Washer.EnumType.ProcessPhase.FinalSpinning' => array(
                'name' => __("Essorag final", __FILE__) ,
                'action' => 'Option'
            ) ,
            'LaundryCare.Washer.Option.IDos1DosingLevel' => array(
                'name' => __("Dosage i-Dos de détergent", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'enum' => array(
                    'LaundryCare.Washer.EnumType.IDosingLevel.Light' => array(
                        'name' => __("Faible", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.IDosingLevel.Normal' => array(
                        'name' => __("Normale", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.IDosingLevel.Middle' => array(
                        'name' => __("Moyenne", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.IDosingLevel.Strong' => array(
                        'name' => __("Forte", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                ),
            ) ,
            'LaundryCare.Washer.Option.IDos2DosingLevel' => array(
                'name' => __("i-DOS: dosage de lessive liquide ou d'adoucissant", __FILE__) ,
                'action' => 'Option',
                'type' => 'Enumeration',
                'enum' => array(
                    'LaundryCare.Washer.EnumType.IDosingLevel.Normal' => array(
                        'name' => __("Dose normale", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.IDosingLevel.Middle' => array(
                        'name' => __("Dose moyenne", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                    'LaundryCare.Washer.EnumType.IDosingLevel.Strong' => array(
                        'name' => __("Forte dose", __FILE__) ,
                        'action' => 'Option'
                    ) ,
                ),
            ) ,
            'LaundryCare.WasherDryer.Program.Cotton' => array(
                'name' => __("Coton", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.WasherDryer.Program.Cotton.Eco4060' => array(
                'name' => __("Coton éco 40-60 min.", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.WasherDryer.Program.EasyCare' => array(
                'name' => __("Synthétiques", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.WasherDryer.Program.Mix' => array(
                'name' => __("Mélangé", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.WasherDryer.Program.Wool.Wool' => array(
                'name' => __("Laine", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.WasherDryer.Program.Rinse.Rinse' => array(
                'name' => __("Rinçage", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.WasherDryer.Program.Rinse.RinseSpin' => array(
                'name' => __("Rinçage/Essorage", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.WasherDryer.Program.WaterProof.WaterProof' => array(
                'name' => __("Imperméabiliser", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.WasherDryer.Program.WashAndDry.60' => array(
                'name' => __("Lavage&Séchage 60 min.", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.WasherDryer.Program.WashAndDry.90' => array(
                'name' => __("Lavage&Séchage 90 min.", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.WasherDryer.Program.Towels.Towels' => array(
                'name' => __("Serviettes", __FILE__) ,
                'action' => 'Program'
            ) ,
            'LaundryCare.WasherDryer.Program.ShirtsBlouses.ShirtsBlouses' => array(
                'name' => __("Chemises", __FILE__) ,
                'action' => 'Program'
            ) ,
            'Refrigeration.FridgeFreezer.Setting.SetpointTemperatureFreezer' => array(
                'name' => __("Température cible congélateur", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Double',
                'available' => array(
                    'Freezer',
                    'FridgeFreezer'
                ) ,
            ) ,
            'Refrigeration.FridgeFreezer.Setting.SetpointTemperatureRefrigerator' => array(
                'name' => __("Température cible réfrigérateur", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Double',
                'available' => array(
                    'FridgeFreezer',
                    'Refrigerator'
                ) ,
            ) ,
            'Refrigeration.FridgeFreezer.Setting.SuperModeFreezer' => array(
                'name' => __("Mode super congélation", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Boolean',
                'available' => array(
                    'Freezer',
                    'FridgeFreezer'
                ) ,
            ) ,
            'Refrigeration.FridgeFreezer.Setting.SuperModeRefrigerator' => array(
                'name' => __("Mode super réfrigération", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Boolean',
                'available' => array(
                    'FridgeFreezer',
                    'Refrigerator'
                ) ,
            ) ,
            'Refrigeration.FridgeFreezer.Event.DoorAlarmFreezer' => array(
                'name' => __("Alarme de porte de congélateur", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'Freezer',
                    'FridgeFreezer'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'Refrigeration.FridgeFreezer.Event.DoorAlarmRefrigerator' => array(
                'name' => __("Alarme de porte de réfrigérateur", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'FridgeFreezer',
                    'Refrigerator'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'Refrigeration.FridgeFreezer.Event.TemperatureAlarmFreezer' => array(
                'name' => __("Alarme de température de congélateur", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'Freezer',
                    'FridgeFreezer'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'Refrigeration.Common.Setting.BottleCooler.SetpointTemperature' => array(
                'name' => __("Température cible glacière", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Double',
                'available' => array(
                    'FridgeFreezer',
                    'Refrigerator'
                ) ,
            ) ,
            'Refrigeration.Common.Setting.ChillerLeft.SetpointTemperature' => array(
                'name' => __("Température cible frigo gauche", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Double',
                'available' => array(
                    'FridgeFreezer',
                    'Refrigerator'
                ) ,
            ) ,
            'Refrigeration.Common.Setting.ChillerCommon.SetpointTemperature' => array(
                'name' => __("Température cible frigo", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Double',
                'available' => array(
                    'FridgeFreezer',
                    'Refrigerator'
                ) ,
            ) ,
            'Refrigeration.Common.Setting.ChillerRight.SetpointTemperature' => array(
                'name' => __("Température cible frigo droit", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Double',
                'available' => array(
                    'FridgeFreezer',
                    'Refrigerator'
                ) ,
            ) ,
            'Refrigeration.Common.Setting.WineCompartment.SetpointTemperature' => array(
                'name' => __("Température cible bac à vins 1", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Double',
                'available' => array(
                    'WineCooler'
                ) ,
            ) ,
            'Refrigeration.Common.Setting.WineCompartment2.SetpointTemperature' => array(
                'name' => __("Température cible bac à vins 2", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Double',
                'available' => array(
                    'WineCooler'
                ) ,
            ) ,
            'Refrigeration.Common.Setting.WineCompartment3.SetpointTemperature' => array(
                'name' => __("Température cible bac à vins 3", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Double',
                'available' => array(
                    'WineCooler'
                ) ,
            ) ,
            'Refrigeration.Common.Setting.EcoMode' => array(
                'name' => __("Mode éco", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Boolean',
                'available' => array(
                    'Freezer',
                    'FridgeFreezer',
                    'Refrigerator'
                ) ,
            ) ,
            'Refrigeration.Common.Setting.SabbathMode' => array(
                'name' => __("Mode Sabbat", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Boolean',
                'available' => array(
                    'Freezer',
                    'FridgeFreezer',
                    'Refrigerator',
                    'WineCooler'
                ) ,
            ) ,
            'Refrigeration.Common.Setting.VacationMode' => array(
                'name' => __("Mode vacances", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Boolean',
                'available' => array(
                    'FridgeFreezer',
                    'Refrigerator'
                ) ,
            ) ,
            'Refrigeration.Common.Setting.FreshMode' => array(
                'name' => __("Mode frais", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Boolean',
                'available' => array(
                    'FridgeFreezer',
                    'Refrigerator'
                ) ,
            ) ,
            'BSH.Common.Root.SelectedProgram' => array(
                'name' => __("Programme sélectionné", __FILE__) ,
                'action' => 'Option',
                'type' => 'String',
                'available' => array(
                    'CoffeeMachine',
                    'Cooktop',
                    'Hood',
                    'Oven',
                    'WarmingDrawer',
                    'CleaningRobot',
                    'Dishwasher',
                    'Dryer',
                    'Washer',
                    'WasherDryer'
                ) ,
            ) ,
            'BSH.Common.Root.ActiveProgram' => array(
                'name' => __("Programme en cours", __FILE__) ,
                'action' => 'Option',
                'type' => 'String',
                'available' => array(
                    'CoffeeMachine',
                    'Cooktop',
                    'Hood',
                    'Oven',
                    'WarmingDrawer',
                    'CleaningRobot',
                    'Dishwasher',
                    'Dryer',
                    'Washer',
                    'WasherDryer'
                ) ,
            ) ,
            'BSH.Common.Option.Duration' => array(
                'name' => __("Ajuster la durée", __FILE__) ,
                'action' => 'Option',
                'type' => 'Int',
                'available' => array(
                    'Hood',
                    'Oven'
                ) ,
            ) ,
            'BSH.Common.Option.StartInRelative' => array(
                'name' => __("Heure de départ", __FILE__) ,
                'action' => 'Option',
                'type' => 'Int',
                'available' => array(
                    'Oven',
                    'Dishwasher'
                )
            ) ,
            'BSH.Common.Option.FinishInRelative' => array(
                'name' => __("Fin différée", __FILE__) ,
                'action' => 'Option',
                'type' => 'Int',
                'available' => array(
                    'Dryer',
                    'Washer',
                    'WasherDryer'
                ),
            ) ,
            'BSH.Common.Option.ElapsedProgramTime' => array(
                'name' => __("Temps de programme écoulé", __FILE__) ,
                'action' => 'Option',
                'type' => 'Int',
                'unit' => 'seconds',
                'constraints' => array(
                    'min' => 0,
                    'max' => 86340
                ),
                'available' => array(
                    'Hood',
                    'Oven',
                    'WarmingDrawer',
                    'CleaningRobot'
                ) ,
            ) ,
            'BSH.Common.Option.RemainingProgramTime' => array(
                'name' => __("Temps de programme restant", __FILE__) ,
                'action' => 'Option',
                'type' => 'Int',
                'unit' => 'seconds',
                'constraints' => array(
                    'min' => 0,
                    'max' => 86340
                ),
                'available' => array(
                    'CoffeeMachine',
                    'Hood',
                    'Oven',
                    'Dishwasher',
                    'Dryer',
                    'Washer',
                    'WasherDryer'
                ) ,
            ) ,
            'BSH.Common.Option.ProgramProgress' => array(
                'name' => __("Progression du programme", __FILE__) ,
                'action' => 'Option',
                'type' => 'Int',
                'unit' => '%',
                'constraints' => array(
                    'min' => 0,
                    'max' => 100
                ),
                'available' => array(
                    'CoffeeMachine',
                    'Hood',
                    'Oven',
                    'WarmingDrawer',
                    'Dishwasher',
                    'Dryer',
                    'Washer',
                    'WasherDryer'
                ) ,
            ) ,
            'BSH.Common.Option.WaterForecast' => array(
                'name' => __("Eau", __FILE__) ,
                'action' => 'Option',
                'type' => 'Int',
                'unit' => '%',
                'constraints' => array(
                    'min' => 0,
                    'max' => 100
                ),
            ) ,
            'BSH.Common.Option.EnergyForecast' => array(
                'name' => __("Énergie", __FILE__) ,
                'action' => 'Option',
                'type' => 'Int',
                'unit' => '%',
                'constraints' => array(
                    'min' => 0,
                    'max' => 100
                ),
            ) ,
            'BSH.Common.Event.ProgramAborted' => array(
                'name' => __("Programme annulé", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'Oven',
                    'CleaningRobot',
                    'Dishwasher',
                    'Dryer',
                    'Washer',
                    'WasherDryer'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'BSH.Common.Event.ProgramFinished' => array(
                'name' => __("Programme terminé", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'available' => array(
                    'Cooktop',
                    'Hood',
                    'Oven',
                    'CleaningRobot',
                    'Dishwasher',
                    'Dryer',
                    'Washer',
                    'WasherDryer'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'BSH.Common.Event.AlarmClockElapsed' => array(
                'name' => __("Temps écoulé", __FILE__) ,
                'action' => 'Event',
                'type' => 'Enumeration',
                'enum' => array(
                    'BSH.Common.EnumType.EventPresentState.Present' => array(
                        'name' => __("Présent", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.EventPresentState.Confirmed' => array(
                        'name' => __("Confirmé", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'BSH.Common.Status.BatteryLevel' => array(
                'name' => __("Niveau de batterie", __FILE__) ,
                'action' => 'Status',
                'type' => 'Float',
                'unit' => '%',
                'constraints' => array(
                    'min' => 0,
                    'max' => 100
                ),
                'available' => array(
                    'CleaningRobot'
                ) ,
            ) ,
            'BSH.Common.Status.BatteryChargingState' => array(
                'name' => __("État de charge de la batterie", __FILE__) ,
                'action' => 'Status',
                'type' => 'Enumeration',
                'available' => array(
                    'CleaningRobot'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.BatteryChargingState.Discharging' => array(
                        'name' => __("En décharge", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.BatteryChargingState.Charging' => array(
                        'name' => __("En charge", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'BSH.Common.Status.ChargingConnection' => array(
                'name' => __("Connexion au chargeur", __FILE__) ,
                'action' => 'Status',
                'type' => 'Enumeration',
                'available' => array(
                    'CleaningRobot'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.ChargingConnection.Disconnected' => array(
                        'name' => __("Déconnecté", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.ChargingConnection.Connected' => array(
                        'name' => __("Connecté", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'BSH.Common.Status.CameraState' => array(
                'name' => __("État de la caméra", __FILE__) ,
                'action' => 'Status'
            ) ,
            'BSH.Common.Status.DoorState' => array(
                'name' => __("Porte", __FILE__) ,
                'action' => 'Status',
                'type' => 'Enumeration',
                'available' => array(
                    'Oven',
                    'Dishwasher',
                    'Dryer',
                    'Washer',
                    'WasherDryer',
                    'Freezer',
                    'FridgeFreezer',
                    'Refrigerator',
                    'WineCooler'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.DoorState.Open' => array(
                        'name' => __("Ouverte", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'Oven',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer',
                            'Freezer',
                            'FridgeFreezer',
                            'Refrigerator',
                            'WineCooler'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.DoorState.Locked' => array(
                        'name' => __("Verrouillée", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'Oven',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.DoorState.Closed' => array(
                        'name' => __("Fermée", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'Oven',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer',
                            'Freezer',
                            'FridgeFreezer',
                            'Refrigerator',
                            'WineCooler'
                        ) ,
                    ) ,
                )
            ) ,
            'BSH.Common.Status.OperationState' => array(
                'name' => __("Statut de fonctionnement", __FILE__) ,
                'action' => 'Status',
                'type' => 'Enumeration',
                'available' => array(
                    'CoffeeMachine',
                    'CookProcessor',
                    'Cooktop',
                    'Hood',
                    'Oven',
                    'WarmingDrawer',
                    'CleaningRobot',
                    'Dishwasher',
                    'Dryer',
                    'Washer',
                    'WasherDryer'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.OperationState.Inactive' => array(
                        'name' => __("Inactif", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CoffeeMachine',
                            'CookProcessor',
                            'Cooktop',
                            'Hood',
                            'Oven',
                            'WarmingDrawer',
                            'CleaningRobot',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.OperationState.Ready' => array(
                        'name' => __("Prêt", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CoffeeMachine',
                            'CookProcessor',
                            'Cooktop',
                            'Oven',
                            'WarmingDrawer',
                            'CleaningRobot',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.OperationState.DelayedStart' => array(
                        'name' => __("Départ différé", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'Cooktop',
                            'Oven',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.OperationState.Run' => array(
                        'name' => __("En fonctionnement", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CoffeeMachine',
                            'CookProcessor',
                            'Cooktop',
                            'Hood',
                            'Oven',
                            'WarmingDrawer',
                            'CleaningRobot',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.OperationState.Pause' => array(
                        'name' => __("Pause", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CookProcessor',
                            'Cooktop',
                            'Oven',
                            'CleaningRobot',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.OperationState.ActionRequired' => array(
                        'name' => __("Action requise", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CoffeeMachine',
                            'CookProcessor',
                            'Cooktop',
                            'Oven',
                            'CleaningRobot',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.OperationState.Finished' => array(
                        'name' => __("Terminé", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CoffeeMachine',
                            'CookProcessor',
                            'Cooktop',
                            'Oven',
                            'CleaningRobot',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.OperationState.Error' => array(
                        'name' => __("Erreur", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CoffeeMachine',
                            'CookProcessor',
                            'Cooktop',
                            'Oven',
                            'WarmingDrawer',
                            'CleaningRobot',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.OperationState.Aborting' => array(
                        'name' => __("Abandon", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CoffeeMachine',
                            'CookProcessor',
                            'Cooktop',
                            'Oven',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.OperationState.RemoteControlStartAllowed' => array(
                        'name' => __("Démarrage à distance", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CoffeeMachine',
                            'Hood',
                            'Oven',
                            'WarmingDrawer',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.OperationState.RemoteControlActive' => array(
                        'name' => __("Réglages par l'appli", __FILE__) ,
                        'action' => 'Status' ,
                        'available' => array(
                            'Cooktop',
                            'Hood',
                            'Oven',
                            'WarmingDrawer',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.OperationState.LocalControlActive' => array(
                        'name' => __("Appareil en fonctionnement", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CoffeeMachine',
                            'Cooktop',
                            'Hood',
                            'Oven',
                            'WarmingDrawer',
                            'Dryer',
                            'Washer',
                            'WasherDryer'
                        ) ,
                    ) ,
                )
            ) ,
            'BSH.Common.Setting.PowerState' => array(
                'name' => __("Statut", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Enumeration',
                'available' => array(
                    'CoffeeMachine',
                    'CookProcessor',
                    'Cooktop',
                    'Hood',
                    'Oven',
                    'WarmingDrawer',
                    'CleaningRobot',
                    'Dishwasher',
                    'Dryer',
                    'Washer',
                    'WasherDryer',
                    'Freezer',
                    'FridgeFreezer',
                    'Refrigerator',
                    'WineCooler'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.PowerState.Off' => array(
                        'name' => __("Désactivé", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'Cooktop',
                            'Hood',
                            'Dishwasher'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.PowerState.On' => array(
                        'name' => __("Activé", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CoffeeMachine',
                            'CookProcessor',
                            'Cooktop',
                            'Hood',
                            'Oven',
                            'WarmingDrawer',
                            'CleaningRobot',
                            'Dishwasher',
                            'Dryer',
                            'Washer',
                            'WasherDryer',
                            'Freezer',
                            'FridgeFreezer',
                            'Refrigerator',
                            'WineCooler'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.PowerState.Standby' => array(
                        'name' => __("Veille", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'Oven',
                            'WarmingDrawer',
                            'CoffeeMachine',
                            'CleaningRobot',
                            'CookProcessor'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.PowerState.MainsOff' => array(
                        'name' => __("Secteur hors tension", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                    'BSH.Common.EnumType.PowerState.Undefined' => array(
                        'name' => __("Non défini", __FILE__) ,
                        'action' => 'Status'
                    ) ,
                )
            ) ,
            'BSH.Common.Status.RemoteControlStartAllowed' => array(
                'name' => __("Démarrage à distance", __FILE__) ,
                'action' => 'Status',
                'type' => 'Boolean'
            ) ,
            'BSH.Common.Status.RemoteControlActive' => array(
                'name' => __("Contrôle à distance", __FILE__) ,
                'action' => 'Status',
                'type' => 'Boolean'
            ) ,
            'BSH.Common.Status.LocalControlActive' => array(
                'name' => __("Contrôle local", __FILE__) ,
                'action' => 'Status',
                'type' => 'Boolean'
            ) ,
            'BSH.Common.Status.Video.CameraState' => array(
                'name' => __("Statut de la caméra", __FILE__) ,
                'action' => 'Status',
                'type' => 'Enumeration',
                'available' => array(
                    'CleaningRobot'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.Video.CameraState.Disabled' => array(
                        'name' => __("Désactivée", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CleaningRobot'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.Video.CameraState.Sleeping' => array(
                        'name' => __("En veille", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CleaningRobot'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.Video.CameraState.Ready' => array(
                        'name' => __("Prête", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CleaningRobot'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.Video.CameraState.StreamingLocal' => array(
                        'name' => __("Streaming local", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CleaningRobot'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.Video.CameraState.StreamingCloud' => array(
                        'name' => __("Streaming cloud", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CleaningRobot'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.Video.CameraState.StreamingLocalAndCloud' => array(
                        'name' => __("Streaming local et cloud", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CleaningRobot'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.Video.CameraState.Error' => array(
                        'name' => __("En erreur", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'CleaningRobot'
                        ) ,
                    ) ,
                )
            ) ,
            'BSH.Common.Setting.TemperatureUnit' => array(
                'name' => __("Unité de température", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Enumeration',
                'available' => array(
                    'CoffeeMachine',
                    'Cooktop',
                    'Oven',
                    'WarmingDrawer',
                    'Freezer',
                    'FridgeFreezer',
                    'Refrigerator',
                    'WineCooler'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.TemperatureUnit.Celsius' => array(
                        'name' => __("Celsius", __FILE__) ,
                        'action' => 'Setting',
                        'available' => array(
                            'CoffeeMachine',
                            'Cooktop',
                            'Oven',
                            'WarmingDrawer',
                            'Freezer',
                            'FridgeFreezer',
                            'Refrigerator',
                            'WineCooler'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.TemperatureUnit.Fahrenheit' => array(
                        'name' => __("Fahrenheit", __FILE__) ,
                        'action' => 'Setting',
                        'available' => array(
                            'CoffeeMachine',
                            'Cooktop',
                            'Oven',
                            'WarmingDrawer',
                            'Freezer',
                            'FridgeFreezer',
                            'Refrigerator',
                            'WineCooler'
                        ) ,
                    ) ,
                )
            ) ,
            'BSH.Common.Setting.LiquidVolumeUnit' => array(
                'name' => __("Unité de volume de liquide", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Enumeration',
                'available' => array(
                    'CoffeeMachine'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.LiquidVolumeUnit.FluidOunces' => array(
                        'name' => __("Onces liquides", __FILE__) ,
                        'action' => 'Setting',
                        'available' => array(
                            'CoffeeMachine'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.LiquidVolumeUnit.MilliLiter' => array(
                        'name' => __("Millilitres", __FILE__) ,
                        'action' => 'Setting',
                        'available' => array(
                            'CoffeeMachine'
                        ) ,
                    ) ,
                )
            ) ,
            'BSH.Common.Setting.ChildLock' => array(
                'name' => __("Sécurité enfants", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Boolean',
                'available' => array(
                    'CoffeeMachine',
                    'Cooktop',
                    'Oven',
                    'WarmingDrawer',
                    'Dishwasher',
                    'Dryer',
                    'Washer',
                    'WasherDryer',
                    'Freezer',
                    'FridgeFreezer',
                    'Refrigerator',
                    'WineCooler'
                ) ,
            ) ,
            'BSH.Common.Setting.AlarmClock' => array(
                'name' => __("Alarme", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Int',
                'unit' => 'seconds',
                'constraints' => array(
                    'min' => 0,
                    'max' => 38340
                ),
                'available' => array(
                    'Cooktop',
                    'Oven'
                ) ,
            ) ,
            'BSH.Common.Setting.AmbientLightEnabled' => array(
                'name' => __("Lumière ambiante activée", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Boolean',
                'available' => array(
                    'Hood',
                    'Dishwasher'
                ) ,
            ) ,
            'BSH.Common.Setting.AmbientLightBrightness' => array(
                'name' => __("Intensité de la lumière ambiante", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Double',
                'unit' => '%',
                'constraints' => array(
                    'min' => 10,
                    'max' => 100
                ),
                'available' => array(
                    'Hood',
                    'Dishwasher'
                ) ,
            ) ,
            'BSH.Common.Setting.AmbientLightColor' => array(
                'name' => __("Couleur de la lumière ambiante", __FILE__) ,
                'action' => 'Setting',
                'type' => 'Enumeration',
                'available' => array(
                    'Hood',
                    'Dishwasher'
                ) ,
                'enum' => array(
                    'BSH.Common.EnumType.AmbientLightColor.CustomColor' => array(
                        'name' => __("Couleur personnalisée", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'Hood',
                            'Dishwasher'
                        ) ,
                    ) ,
                    'BSH.Common.EnumType.AmbientLightColor.Color[1-99]' => array(
                        'name' => __("Couleur [1-99]", __FILE__) ,
                        'action' => 'Status',
                        'available' => array(
                            'Hood',
                            'Dishwasher'
                        ) ,
                    ) ,
                )
            ) ,
            'BSH.Common.Setting.AmbientLightCustomColor' => array(
                'name' => __("Couleur personnalisée de la lumière ambiante", __FILE__) ,
                'action' => 'Setting',
                'type' => 'String',
                'available' => array(
                    'Hood',
                    'Dishwasher'
                ) ,
            ) ,
            'BSH.Common.Command.PauseProgram' => array(
                'name' => __("Pause", __FILE__) ,
                'Command',
                'type' => 'Boolean'
            ) ,
            'BSH.Common.Command.ResumeProgram' => array(
                'name' => __("Reprendre", __FILE__) ,
                'Command',
                'type' => 'Boolean'
            ) ,
        );
        return $KEYS;
    }

	/** *************************** Attributs ********************************* */

	public static $_widgetPossibility = array('custom' => true);

	/** *************************** Attributs statiques *********************** */



	/** *************************** Méthodes ********************************** */



	/** *************************** Méthodes statiques ************************ */

  	public static function getCmdValueTranslation($_key, $_value) {
	/**
	 * Récupère la traduction de la valeur d'une commande
	 *
	 * @param	$_key		string		Clé de la commande
	 * @param	$_value		string		Valeur brute de la clé
	 * @return	$return		string		Valeur traduite de la clé
	 */
        $return = $_value;
		$tableData = self::appliancesCapabilities();
		if(array_key_exists($_key, $tableData)){
		    if(array_key_exists('enum', $tableData[$_key])){
		        if(array_key_exists($_value, $tableData[$_key]['enum'])){
                    $return = $tableData[$_key]['enum'][$_value]['name'];
                }
            } elseif (array_key_exists($_value, $tableData)) {
                $return = $tableData[$_value]['name'];
            } else {
			    log::add(__CLASS__,'debug',__FUNCTION__ . ' La clé ' . $_key . ' existe, mais valeur ' . $_value . ' est introuvable');
            }
		} else {
		    log::add(__CLASS__,'debug',__FUNCTION__ . ' La clé ' . $_key . ' est introuvable');
        }
        return $return;
    }

		public static function getCmdNameTranslation($_key) {
			/**
			* Récupère la traduction du nom d'une commande
			*
			* @param	$_key		string		Clé de la commande
			* @return	$return		string		Valeur traduite de la clé
			*/
			$tableData = self::appliancesCapabilities();
			if (isset($tableData[$_key])) {
				return $tableData[$_key]['name'];
			} else {
				log::add(__CLASS__,'debug',__FUNCTION__ . ' La clé ' . $_key . ' est introuvable');
			}
			return false;
		}

	public static function baseUrl() {
		if (config::byKey('demo_mode','homeconnect')) {
			return "https://simulator.home-connect.com";
		} else {
			return "https://api.home-connect.com";
		}
	}
	protected static function buildQueryString(array $params) {
		return http_build_query($params, null, '&', PHP_QUERY_RFC3986);
	}

	public static function lastSegment($separator, $key) {
		if (strpos($key, $separator) === false) {
			return '';
		}
		$parts = explode($separator, $key);
		return $parts[count($parts) - 1];
	}

	public static function firstSegment($separator, $key) {
		if (strpos($key, $separator) === false) {
			return '';
		}
		$parts = explode($separator, $key);
		return $parts[0];
	}

	public static function request($url, $payload = null, $method = 'POST', $headers = array()) {
		$ch = curl_init(self::baseUrl() . $url);

		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

		$requestHeaders = [
			"Accept: application/vnd.bsh.sdk.v1+json",
			"Accept-Language: " . config::byKey('language', 'core', 'fr_FR'),
			"Authorization: Bearer ".config::byKey('access_token','homeconnect'),
		];

		if($method == 'POST' || $method == 'PUT') {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			$requestHeaders[] = 'Content-Type: application/json';
			$requestHeaders[] = 'Content-Length: ' . strlen($payload);
		}

		if(count($headers) > 0) {
			$requestHeaders = array_merge($requestHeaders, $headers);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
		// curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 7.0; SM-G930F Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/64.0.3282.137 Mobile Safari/537.36');

		$result = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$totalRequests = intval(cache::byKey('homeconnect::requests::total')->getValue());
		$totalRequests++;
        cache::set('homeconnect::requests::total',$totalRequests,'');
		log::add('homeconnect','debug',"Nombre de requêtes envoyées aujourd'hui " . $totalRequests);

		if ($code == '200' || $code == '204') {
			log::add('homeconnect','debug',"La requête $method	: $url a réussi code = " . $code . " résultat = ".$result);
			return $result;
		} else {
			// Traitement des erreurs
			log::add('homeconnect','debug',"La requête $method	: $url a retourné un code d'erreur " . $code . " résultat = ".$result);
			switch ($code) {
				case 400:
					// "Bad Request", desc: "Error occurred (e.g. validation error - value is out of range)"
					break;
				case 401:
					// "Unauthorized", desc: "No or invalid access token"
					throw new \Exception(__("Le jeton d'authentification au serveur est absent ou invalide. Reconnectez-vous",__FILE__));
					break;
				case 403:
					// Forbidden", desc: "Scope has not been granted or home appliance is not assigned to HC account"
					throw new \Exception(__("Accès à cette ressource non autorisé ou appareil non lié à cet utilisateur",__FILE__));
					break;
				case 404:
					$result = json_decode($result, true);
					if ($result['error']['key'] == 'SDK.Error.NoProgramActive' || $result['error']['key'] == 'SDK.Error.NoProgramSelected' || $result['error']['key'] == 'SDK.Error.UnsupportedProgram') {
						return $result['error']['key'];
					}
					// Not Found", desc: "This resource is not available (e.g. no images on washing machine)"
					throw new \Exception(__("Cette ressource n'est pas disponible",__FILE__));
					break;
				case 405:
					// "Method not allowed", desc: "The HTTP Method is not allowed for this resource" },
					throw new \Exception(__("La méthode $method n'est pas permise pour cette ressource",__FILE__));
					break;
				case 406:
					// "Not Acceptable", desc: "The resource identified by the request is only capable of generating response entities which have content characteristics not acceptable according to the accept headers sent in the request."
					throw new \Exception(__("Impossible de fournir une réponse Les entêtes 'Accept' de la requête ne sont pas acceptés",__FILE__));
					break;
				case 408:
					// "Request Timeout", desc: "API Server failed to produce an answer or has no connection to backend service"
					throw new \Exception(__("Le serveur n'a pas fourni de réponse dans le temps imparti",__FILE__));
					break;
				case 409:
					// "Conflict", desc: "Command/Query cannot be executed for the home appliance, the error response contains the error details"
					$result = json_decode($result, true);
					$errorMsg = isset($result['error']['description']) ? $result['error']['description'] : '';
					throw new \Exception(__("Cette action ne peut pas être exécutée pour cet appareil",__FILE__) . ' ' . $errorMsg);
					break;
				case 415:
					// "Unsupported Media Type", desc: "The request's Content-Type is not supported"
					throw new \Exception(__("Le type de contenu de la requête n'est pas pris en charge",__FILE__));
					break;
				case 429:
					//	"Too Many Requests", desc: "E.g. the number of requests for a specific endpoint exceeded the quota of the client"
					throw new \Exception(__("Vous avez dépassé le nombre de requêtes permises au serveur. Réessayez dans 24h",__FILE__));
					break;
				case 500:
					// "Internal Server Error", desc: "E.g. in case of a server configuration error or any errors in resource files"
					throw new \Exception(__("Erreur interne du serveur",__FILE__));
					break;
				case 503:
					// "Service Unavailable", desc: "E.g. if a required backend service is not available"
					throw new \Exception(__("Service indisponible",__FILE__));
					break;
				default:
				   // Erreur inconnue
				   throw new \Exception(__("Erreur inconnue code " . $code,__FILE__));
			}
			return false;
		}
	}

	public static function syncHomeConnect($_forced) {
	/**
	 * Connexion au compte Home Connect (via token) et récupération des appareils liés.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */
		log::add('homeconnect', 'debug',"Fonction syncHomeConnect()");
		if (empty(config::byKey('auth','homeconnect'))) {
			log::add('homeconnect', 'debug', "[Erreur] : Code d’authentifiaction vide.");
			throw new Exception("Erreur : Veuillez vous connecter à votre compte Home Connect via le menu configuration du plugin.");
			return;
		}

		// Pas besoin de vérifier le token, homeappliances le fait

		// Récupération des appareils.
		self::homeappliances($_forced);

		log::add('homeconnect', 'debug',"Fin de la fonction syncHomeConnect()");
	}

	public static function updateAppliances(){
	/**
	 * Lance la mise à jour des informations des appareils (lancement par cron).
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"Fonction updateAppliances()");

		self::verifyToken(60);

		// MAJ du statut de connexion des appareils.
		self::majConnected();

		foreach (eqLogic::byType('homeconnect') as $eqLogic) {
			// MAJ des programmes en cours.
			$eqLogic->updateProgram();
			// MAJ des états
			$eqLogic->updateStates();
			// MAJ des réglages
			$eqLogic->updateSettings();
			if ($eqLogic->getIsEnable()) {
				$eqLogic->refreshWidget();
			}
		}
		log::add('homeconnect', 'debug',"Fin de la fonction updateAppliances()");
	}

	public static function authRequest() {
	/**
	 * Construit l'url d'authentification.
	 *
	 * @param			|*Cette fonction ne prend pas de paramètres*|
	 * @return			|*Cette fonction retourne l'url d'authentification*|
	 */
		log::add('homeconnect', 'debug',"Fonction authRequest()");
		@session_start();
		$authorizationUrl = self::baseUrl() . self::API_AUTH_URL;
		$clientId = config::byKey('client_id','homeconnect','',true);
		$redirectUri = urlencode(network::getNetworkAccess('external') . '/plugins/homeconnect/core/php/callback.php?apikey=' . jeedom::getApiKey('homeconnect'));
		if (config::byKey('demo_mode','homeconnect')) {
			$parameters['scope'] = implode(' ', ['IdentifyAppliance', 'Monitor', 'Settings',
				'CoffeeMaker-Control', 'Dishwasher-Control', 'Dryer-Control', 'Washer-Control']);
			$parameters['user'] = 'me'; // Can be anything non-zero length
			$parameters['client_id'] = config::byKey('demo_client_id','homeconnect','',true);
		} else {
			$parameters['scope'] = implode(' ', ['IdentifyAppliance', 'Monitor', 'Settings', 'Control']);
			$parameters['redirect_uri'] = network::getNetworkAccess('external') . '/plugins/homeconnect/core/php/callback.php?apikey=' . jeedom::getApiKey('homeconnect');
			$parameters['client_id'] = config::byKey('client_id','homeconnect','',true);
		}
		$parameters['response_type'] = 'code';
		$state = bin2hex(random_bytes(16));
		$_SESSION['oauth2state'] = $state;
		$parameters['state'] = $state;
        cache::set('homeconnect::state',$state,600);
		// Construction de l'url.
		$url = $authorizationUrl ."?" . self::buildQueryString($parameters);
		log::add('homeconnect', 'debug',"url = " . $url);
		log::add('homeconnect', 'debug',"Fin de la fonction authRequest()");
		return $url;
	}

	public static function authDemoRequest() {
	/**
	 * Récupère un code d'authorisation à échanger contre un token.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"Fonction authRequest()");

		// Construction de l'url.
		$url = self::authRequest();

		// Envoie d'une requête GET et récupération du header.
		$curl = curl_init();
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => True,
			CURLOPT_SSL_VERIFYPEER => False,
			CURLOPT_HEADER => True,
			CURLINFO_HEADER_OUT => true,
			];
		curl_setopt_array($curl, $options);
		$response = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);

		// Vérification du code réponse.
		if ($info['http_code'] != 302) {

			// Récupération du message d'erreur pour log.
			preg_match("/[\{].*[\}]/", $response, $matches);
			log::add('homeconnect', 'debug', "[Erreur] (code erreur : ".$info['http_code'].") : ".print_r($matches, true));
			throw new Exception("Erreur : " . print_r($matches));
			return;
		}

		$params = parse_url($info['redirect_url']); // Récupération de l'url de redirection avec paramêtre.
		$params = explode("&",$params['query']); // Explode des paramêtres de l'url afin d'isoler l'authorize code.

		// Récupération du code d'authorisation.
		foreach($params as $key => $value) {

			$explode = explode("=", $value);

			if ($explode[0] == "code") {

				config::save('auth', $explode[1], 'homeconnect');
				log::add('homeconnect', 'debug', "Code d'authorisation récupéré (".$explode[1].".");
				homeconnect::tokenRequest();
			}
		}

		log::add('homeconnect', 'debug',"Fin de la fonction authRequest()");
	}

	public static function tokenRequest() {
	/**
	 * Récupère un token permettant l'accès au serveur.
	 *
	 * @param			|*Cette fonction ne prend pas de paramètres*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"Fonction tokenRequest()");
		if (!config::byKey('demo_mode','homeconnect')) {
			$clientId = config::byKey('client_id','homeconnect','',true);
		} else {
			$clientId = config::byKey('demo_client_id','homeconnect','',true);
		}
		// Vérification de la présence du code d'authorisation avant de demander le token.
		if (empty(config::byKey('auth','homeconnect'))) {

			log::add('homeconnect', 'debug', "[Erreur] : Code d'authorisation vide.");
			throw new Exception("Erreur : Veuillez connecter votre compte via le menu configuration du plugin.");
			return;
		}
		$url = self::baseUrl() . self::API_TOKEN_URL;
		log::add('homeconnect', 'debug', "Url = ". $url);

		// Création du paramêtre POSTFIELDS.
		$parameters = array();
		$parameters['client_id'] = $clientId;
		if (!config::byKey('demo_mode','homeconnect')) {
			$parameters['client_secret'] = config::byKey('client_secret','homeconnect','',true);
		}
		$parameters['redirect_uri'] = network::getNetworkAccess('external') . '/plugins/homeconnect/core/php/callback.php?apikey=' . jeedom::getApiKey('homeconnect');
		$parameters['grant_type'] = 'authorization_code';
		$parameters['code'] = config::byKey('auth','homeconnect');
		log::add('homeconnect', 'debug', "Post fields = ". json_encode($parameters));

		// Récupération du Token.
		$curl = curl_init();
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => True,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_POST => True,
			CURLOPT_POSTFIELDS => self::buildQueryString($parameters),
			];
		curl_setopt_array($curl, $options);
		$response = json_decode(curl_exec($curl), true);
		log::add('homeconnect', 'debug', "Response = ". print_r($response, true));
		$http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
		curl_close ($curl);

		// Vérification du code réponse.
		if($http_code != 200) {

			log::add('homeconnect', 'debug', "[Erreur] (code erreur : ".$http_code.") : Impossible de récupérer le token.");
			throw new Exception("Erreur : Impossible de récupérer le token (code erreur : ".$http_code.").");
			return;

		} else {

			log::add('homeconnect', 'debug', "Token récupéré.");
		}

		// Calcul de l'expiration du token.
		$expires_in = time() + $response['expires_in'];

		// Enregistrement des informations dans le plugin.
		config::save('access_token', $response['access_token'], 'homeconnect');
		config::save('refresh_token', $response['refresh_token'], 'homeconnect');
		config::save('token_type', $response['token_type'], 'homeconnect');
		config::save('scope', $response['scope'], 'homeconnect');
		config::save('expires_in', $expires_in, 'homeconnect');
		config::save('id_token', $response['id_token'], 'homeconnect');

		log::add('homeconnect', 'debug',"Access token : ".$response['access_token']);
		log::add('homeconnect', 'debug',"Refresh token : ".$response['refresh_token']);
		log::add('homeconnect', 'debug',"Token type : ".$response['token_type']);
		log::add('homeconnect', 'debug',"scope : ".$response['scope']);
		log::add('homeconnect', 'debug',"Expires in : ".$expires_in);
		log::add('homeconnect', 'debug',"Id token : ".$response['id_token']);
		log::add('homeconnect', 'debug',"Fin de la fonction tokenRequest()");
	}

	public static function tokenRefresh() {
	/**
	 * Rafraichit un token expiré permettant l'accès au serveur.
	 *
	 * @param			|*Cette fonction ne prend pas de paramètres*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"Fonction tokenRefresh()");

		// Vérification de la présence du code d'authorisation avant de demander le token.
		if (empty(config::byKey('auth','homeconnect'))) {

			log::add('homeconnect', 'debug', "[Erreur] : Code d'authorisation vide.");
			throw new Exception("Erreur : Veuillez connecter votre compte via le menu configuration du plugin.");
			return;
		}
		$url = self::baseUrl() . self::API_TOKEN_URL;
		log::add('homeconnect', 'debug', "Url = ". $url);

		// Création du paramêtre POSTFIELDS.
		$parameters = array();
		$parameters['grant_type'] = 'refresh_token';
		if (!config::byKey('demo_mode','homeconnect')) {
			$parameters['client_secret'] = config::byKey('client_secret','homeconnect','',true);
		}
		$parameters['refresh_token'] = config::byKey('refresh_token','homeconnect','',true);
		log::add('homeconnect', 'debug', "Post fields = ". json_encode($parameters));

		// Récupération du Token.
		$curl = curl_init();
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => True,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_POST => True,
			CURLOPT_POSTFIELDS => self::buildQueryString($parameters),
			];
		curl_setopt_array($curl, $options);
		$response = json_decode(curl_exec($curl), true);
		log::add('homeconnect', 'debug', "Response = ". print_r($response, true));
		$http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
		curl_close ($curl);

		$tokenRequests = intval(cache::byKey('homeconnect::requests::refresh_token')->getValue());
        $tokenRequests++;
        cache::set('homeconnect::requests::refresh_token',$tokenRequests,'');

		// Vérification du code réponse.
		if($http_code != 200) {

			log::add('homeconnect', 'debug', "[Erreur] (code erreur : ".$http_code.") : Impossible de rafraichir le token.");
			throw new Exception("Erreur : Impossible de rafraichir le token (code erreur : ".$http_code.").");
			return;

		} else {

			log::add('homeconnect', 'debug', "Token rafraichi.");
		}

		// Calcul de l'expiration du token.
		$expires_in = time() + $response['expires_in'];

		// Enregistrement des informations dans le plugin.
		config::save('access_token', $response['access_token'], 'homeconnect');
		config::save('refresh_token', $response['refresh_token'], 'homeconnect');
		config::save('token_type', $response['token_type'], 'homeconnect');
		config::save('scope', $response['scope'], 'homeconnect');
		config::save('expires_in', $expires_in, 'homeconnect');
		config::save('id_token', $response['id_token'], 'homeconnect');

		log::add('homeconnect', 'debug',"Access token : ".$response['access_token']);
		log::add('homeconnect', 'debug',"Refresh token : ".$response['refresh_token']);
		log::add('homeconnect', 'debug',"Token type : ".$response['token_type']);
		log::add('homeconnect', 'debug',"scope : ".$response['scope']);
		log::add('homeconnect', 'debug',"Expires in : ".$expires_in);
		log::add('homeconnect', 'debug',"Id token : ".$response['id_token']);
		log::add('homeconnect', 'debug',"Fin de la fonction tokenRefresh()");
	}

	public static function verifyToken($delay) {
		// Vérification si le token est expiré.
		if ((config::byKey('expires_in','homeconnect') - time()) < $delay) {

			log::add('homeconnect', 'debug', "[Warning] : Le token est expiré, renouvellement de ce dernier.");

			// Récupération du token d'accès aux serveurs.
			// ne pas oublier d'arrêter le deamon avant
			self::tokenRefresh();
		}

		// Vérification de la présence du token et tentative de récupération si absent.
		if (empty(config::byKey('access_token','homeconnect'))) {

			log::add('homeconnect', 'debug', "[Warning] : Le token est manquant, récupération de ce dernier.");

			// Récupération du token d'accès aux serveurs.
			self::tokenRequest();

			if (empty(config::byKey('access_token','homeconnect'))) {
				log::add('homeconnect', 'debug', "[Erreur]: La récupération du token a échoué.");
				return;
			}
			// Dans le cas contraire relancer le deamon
		}
		// Relancer le deamon
	}

	private static function homeappliances($_forced) {
	/**
	 * Récupère la liste des appareils connectés et création des objets associés.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"---------- Début de synchronisation ---------- (forcée =" . $_forced . ')');

		self::verifyToken(60);

		$response = self::request(self::API_REQUEST_URL, null, 'GET', array());
		$response = json_decode($response, true);
		foreach($response['data']['homeappliances'] as $key => $appliance) {
		/*	haId = Id de l'appareil
			vib = modèle de l'appareil
			brand = marque de l'appareil
			type = type de l'appareil
			name = nom de l'appareil
			enumber = N° de série
			connected = boolean */

			// Vérification que l'appareil n'est pas déjà créé.
			$eqLogic = eqLogic::byLogicalId($appliance['haId'], 'homeconnect');

			if (!is_object($eqLogic)) {
				event::add('jeedom::alert', array(
					'level' => 'warning',
					'page' => 'homeconnect',
					'message' => __("Nouvel appareil detecté", __FILE__). ' ' .$appliance['name'],
				));
				// Création de l'appareil.
				$eqLogic = new homeconnect();
				$eqLogic->setLogicalId($appliance['haId']);
				$eqLogic->setIsEnable(1);
				$eqLogic->setIsVisible(1);
				$defaultRoom = intval(config::byKey('defaultParentObject','homeconnect','',true));
				if($defaultRoom) $eqLogic->setObject_id($defaultRoom);
				$eqLogic->setEqType_name('homeconnect');
				$eqLogic->setName($appliance['name']);
				$eqLogic->setConfiguration('haid', $appliance['haId']);
				$eqLogic->setConfiguration('vib', $appliance['vib']);
				$eqLogic->setConfiguration('brand', $appliance['brand']);
				$eqLogic->setConfiguration('type', $appliance['type']);
				$eqLogic->save();
				$found_eqLogics = self::findProduct($appliance);
				$_forced = true; // forcer la récupération de tous les programmes/settings si l'appareil n'existe pas...
			}
			if (is_object($eqLogic)) {
				// certains apareils ne répondent pas pour les programmes et options s'ils ne sont pas connectés
				if ($appliance['connected'] && $_forced) {
                    // Programs
                    if ($appliance['type'] !== 'Refrigerator' && $appliance['type'] !== 'FridgeFreezer' && $appliance['type'] !== 'WineCooler') {
                        $programs = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/programs', null, 'GET', array());
                        if ($programs !== false) {
                            $programs = json_decode($programs, true);
                            if (isset($programs['data']['programs'])) {
                                $eqLogic->setConfiguration('hasPrograms', true);
                                foreach($programs['data']['programs'] as $applianceProgram) {
                                    $programdata = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/programs/available/' . $applianceProgram['key'], null, 'GET', array());
                                    log::add('homeconnect','debug', 'Appliance Program ' . print_r($programdata, true));
                                    if ($programdata !== false && $programdata !== 'SDK.Error.UnsupportedProgram') {
                                        $programdata = json_decode($programdata, true);
                                        if (isset($applianceProgram['constraints']['execution'])) {
                                            if ($applianceProgram['constraints']['execution'] !== 'selectandstart') {
                                                $path = 'programs/active';
                                            } else {
                                                $path = 'programs/selected';
                                            }
                                        } else {
                                            $path = 'programs/selected';
                                        }
                                        if (isset($programdata['data']['key'])) {
                                            // Création de la commande action programme
                                            $actionCmd = $eqLogic->createActionCmd($programdata['data'], $path , 'Program');
                                            if ($path == 'programs/selected') {
                                                $infoCmd = $eqLogic->getCmd('info', 'GET::BSH.Common.Root.SelectedProgram');
                                                if (is_object($infoCmd)) {
                                                    // On a trouvé la commande info associée.
                                                    log::add('homeconnect', 'debug', "setValue sur la commande programme selected " . $actionCmd->getLogicalId() . " commande info " .$infoCmd->getLogicalId());
                                                    $actionCmd->setValue($infoCmd->getId());
                                                    $actionCmd->save();
                                                } else {
                                                    log::add('homeconnect', 'debug', "Pas de commande info GET::BSH.Common.Root.SelectedProgram");
                                                }
                                            } else if ($path == 'programs/active') {
                                                $infoCmd = $eqLogic->getCmd('info', 'GET::BSH.Common.Root.ActiveProgram');
                                                if (is_object($infoCmd)) {
                                                    // On a trouvé la commande info associée.
                                                    log::add('homeconnect', 'debug', "setValue sur la commande programme active " . $actionCmd->getLogicalId() . " commande info " .$infoCmd->getLogicalId());
                                                    $actionCmd->setValue($infoCmd->getId());
                                                    $actionCmd->save();
                                                    // A voir : ne pas la rendre visible ?
                                                } else {
                                                    log::add('homeconnect', 'debug', "Pas de commande info GET::BSH.Common.Root.ActiveProgram");
                                                }
                                            }
                                        }
                                        if (isset($programdata['data']['options'])) {
                                            log::add('homeconnect', 'debug', "Création des commandes options " . print_r($programdata['data']['options'], true) . ' - path ' . $path);
                                            // creation des commandes option action et info
                                            foreach($programdata['data']['options'] as $optionData) {
                                                $eqLogic->createProgramOption($path, $optionData);
                                            }
                                        } else {
                                            log::add('homeconnect', 'debug', "Aucune commande option");
                                        }
                                    } else {
                                        log::add('homeconnect', 'debug', "La requête /programs/available/ a retourné false");
                                    }
                                }
                            } else {
                                log::add('homeconnect', 'debug',"Cet appareil n'a pas de programmes");
                                $eqLogic->setConfiguration('hasPrograms', false);
                            }
                        } else {
                            log::add('homeconnect', 'debug',"La requête /programs a retourné false");
                            $eqLogic->setConfiguration('hasPrograms', false);
                        }
                    } else {
                        log::add('homeconnect', 'debug',"Ce type d'appareil n'a pas de programme");
                        $eqLogic->setConfiguration('hasPrograms', false);
                    }

                    // Status
                    $allStatus = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/status', null, 'GET', array());
                    if ($allStatus !== false) {
                        $allStatus = json_decode($allStatus, true);
                        if (isset($allStatus['data']['status'])) {
                            foreach($allStatus['data']['status'] as $statusData) {
                                log::add('homeconnect', 'debug', "Status " . print_r($statusData, true));
                                $eqLogic->createInfoCmd($statusData, 'status/' . $statusData['key'], 'Status');
                            }
                        } else {
                            log::add('homeconnect','debug', "Aucun status");
                        }
                    }

                    // Settings
                    $allSettings = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/settings', null, 'GET', array());
                    log::add('homeconnect', 'debug', "tous les Settings " . $allSettings);
                    if ($allSettings !== false) {
                        $allSettings = json_decode($allSettings, true);
                        if (isset($allSettings['data']['settings'])) {
                            foreach($allSettings['data']['settings'] as $setting) {
                                log::add('homeconnect', 'debug', "setting key " . $setting['key']);
                                $path = 'settings/' . $setting['key'];
                                $settingData = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/' . $path, null, 'GET', array());
                                if ($settingData !== false) {
                                    log::add('homeconnect', 'debug', "Setting " . $settingData);
                                    $settingData = json_decode($settingData, true);
                                    // A voir si pas d'access on assume readWrite. est-ce correct ?
                                    if (isset($settingData['data']['constraints']['access']) && $settingData['data']['constraints']['access'] == 'readWrite') {
                                        log::add('homeconnect', 'debug', "Le settin est readWrite, on crée aussi la commande setting action");
                                        $actionCmd = $eqLogic->createActionCmd($settingData['data'], $path, 'Setting');
                                        log::add('homeconnect', 'debug', "On crée aussi la commande setting info");
                                        $infoCmd = $eqLogic->createInfoCmd($settingData['data'], $path, 'Setting', $actionCmd);
                                        // le setValue est fait dans createInfoCmd
                                    } else {
                                        // Commande info sans commande action associée
                                        log::add('homeconnect', 'debug', "Le setting est non readWrite, on ne crée que la commande setting info");
                                        $infoCmd = $eqLogic->createInfoCmd($settingData['data'], $path, 'Setting');
                                    }
                                }
                            }
                        } else {
                            log::add('homeconnect','debug', "Aucun setting");
                        }
                    }
				} else {
                    if ($_forced) {
                        // L'appareil n'est pas connecté
                        event::add('jeedom::alert', array(
                            'level' => 'danger',
                            'page' => 'homeconnect',
                            'message' => __("L'appareil n'est pas connecté. Merci de le connecter et de refaire une synchronisation", __FILE__),
                        ));
                        sleep(3);
                    } else {
                        log::add('homeconnect','debug', "L'appareil est connecté, mais les program/settings n'ont pas été demandés");
                    }
				}
			} else {
			    $eqLogic->applyModuleConfiguration(true);
            }
		}
		log::add('homeconnect', 'debug',"---------- Fin de synchronisation ----------");
	}

	private static function majConnected() {
	/**
	 * Récupère le statut connecté des l'appareils.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"Fonction majConnected()");

		// A voir si l'appareil vient de se connecter n'y aurait-il pas des choses à faire ?
		$response = self::request(self::API_REQUEST_URL, null, 'GET', array());
		$response = json_decode($response, true);
		foreach($response['data']['homeappliances'] as $key) {
			/* connected = boolean */

			$eqLogic = eqLogic::byLogicalId($key['haId'], 'homeconnect');
			if (is_object($eqLogic) && $eqLogic->getIsEnable()){
				$cmd = $eqLogic->getCmd('info', 'connected');
				if (is_object($cmd)) {
					$eqLogic->checkAndUpdateCmd($cmd, $key['connected']);

					log::add('homeconnect', 'debug', "MAJ du status connected " . $eqLogic->getConfiguration('type', '') . ' ' . $eqLogic->getConfiguration('haId', '') . ' Valeur : '. $key['connected'] ? "Oui" : "Non");

				} else {
					log::add('homeconnect', 'debug', "Erreur La commande connected n'existe pas :" . $eqLogic->getConfiguration('type', '') . ' ' . $eqLogic->getConfiguration('haId', ''));
				}
			}
		}

		log::add('homeconnect', 'debug',"Fin de la fonction majConnected()");
	}

	public static function findProduct($_appliance) {
		$eqLogic = self::byLogicalId($_appliance['haId'], 'homeconnect');
		$eqLogic->loadCmdFromConf($_appliance['type']);
		return $eqLogic;
	}

	public static function devicesParameters($_type = '') {
		$return = array();
		$file = dirname(__FILE__) . '/../config/types/' . $_type . '.json';
		if (!is_file($file)) {
			return false;
		}

		try {
			$content = file_get_contents($file);
			if (is_json($content)) {
				$return += json_decode($content, true);
			}
		} catch (Exception $e) {
			log::add('homeconnect', 'info', 'Fichier ' . $file . ' erroné');
		}
		return $return;
	}

    public static function getEvents($ch, $string){
	/**
	 * Récupère tous les évenements et instruit les commandes
	 *
	 * @param	$ch			objet		Session
	 * @param	$string		string		Chaîne d'événement reçue
	 * @return	$length		string		Longueur de la chaine.
	 */

        $length = strlen($string);

        $isError = json_decode($string, true);
        if (is_array($isError) && array_key_exists('error', $isError)) {
            if (array_key_exists('key', $isError['error']) && $isError['error']['key'] == 'invalid_token') {
                log::add('homeconnectd', 'info', 'Régénération du token demandée');
                self::tokenRefresh();
                self::deamon_start();
            }
        }

        $events = array();
        log::add('homeconnectd', 'info', 'Événement bruts : ' . $string);
        foreach (explode("\r\n", $string) as $line) {
            if (strstr($line, 'event:')) {
                $event = array('haId' => NULL,'event' => NULL,'data' => array());
                foreach (explode("\n", $line) as $event_data) {
                    if (strstr($event_data, 'event:')) {
                        $event['event'] = trim(strtolower(substr($event_data, 6)));
                    } else if (strstr($event_data, 'id:')) {
                        $event['haId'] = trim(substr($event_data, 3));
                    } else if (strstr($event_data, 'data:')) {
                        if ($json = json_decode(trim(substr($event_data, 5)), true)) {
                            $event['data'] = $json;
                        }
                    }
                }
                if ($event['haId']) {
                    $events[] = $event;
                }
            }
        }
        log::add('homeconnectd', 'info', 'Événement capturés : ' . print_r($events, true));

        foreach ($events as $evenement) {
            if ($evenement['data'] && isset($evenement['data']['items'])) {
                foreach ($evenement['data']['items'] as $items) {
                    $eqLogic = eqLogic::byLogicalId($evenement['haId'], 'homeconnect');
                    if (is_object($eqLogic) && $eqLogic->getIsEnable()){
                        $cmdLogicalId = 'GET::' . $items['key'];
                        $cmd = $eqLogic->getCmd('info', $cmdLogicalId);
                        if (!is_object($cmd)) {
                            $eqLogic->createInfoCmd($items, $items['key'], 'Option');
                        }
                        $eqLogic->updateInfoCmdValue($cmdLogicalId, $items);
                    } else {
                        log::add('homeconnect', 'debug', 'Appareil ' . $array['haId'] . 'n\'existe pas ou n\'est pas activé');
                    }
                }
            }
        }
        return $length; //important de renvoyer la taille
    }

	private static function traduction($word){
	/**
	 * Traduction des informations.
	 *
	 * @param	$word		string		Mot en anglais.
	 * @return	$word		string		Mot en Français (ou anglais, si traduction inexistante).
	 */

		$translate = [
				//DISHWASHER
				//Dishcare.Dishwasher.Program
				'Program' => __("Programme", __FILE__),
				'Auto1' => __("Auto 35-45°C", __FILE__),
				'Auto2' => __("Auto 45-65°C", __FILE__),
				'Auto3' => __("Auto 65-75°C", __FILE__),
				'PreRinse' => __("Pré-rinçage", __FILE__),
				'Eco50' => __("Eco 50°C", __FILE__),
				'Quick45' => __("Rapide 45°C", __FILE__),
				'Intensiv70' => __("Intensif 70°C", __FILE__),
				'Normal65' => __("Normal 65°C", __FILE__),
				'Glas40' => __("Verres 40°C", __FILE__),
				'GlassCare' => __("Soin des verres", __FILE__),
				'NightWash' => __("Silence 50°C", __FILE__),
				'Quick65' => __("Rapide 65°C", __FILE__),
				'Normal45' => __("Normal 45°C", __FILE__),
				'Intensiv45' => __("Intensif 45°C", __FILE__),
				'AutoHalfLoad' => __("Auto demi-charge", __FILE__),
				'IntensivPower' => __("Puissance intensive", __FILE__),
				'MagicDaily' => __("Magie quotidienne", __FILE__),
				'Super60' => __("Super 60°C", __FILE__),
				'Kurz60' => __("Court 60°C", __FILE__),
				'ExpressSparkle65' => __("Rapide étincellant 65°C", __FILE__),
				'MachineCare' => __("Soin de la machine", __FILE__),
				'SteamFresh' => __("Rinçage et séchage hygiénique", __FILE__),
				'MaximumCleaning' => __("Nettoyage complet", __FILE__),
				//Dishcare.Dishwasher.Option
				'IntensivZone' => __("Zone intensive", __FILE__),
				'BrillianceDry' => __("Brillance à sec", __FILE__),
				'VarioSpeedPlus' => __("VarioSpeed +", __FILE__),
				'SilenceOnDemand' => __("Silence sur demande", __FILE__),
				'HalfLoad' => __("Demi-charge", __FILE__),
				'ExtraDry' => __("Extra sec", __FILE__),
				'HygienePlus' => __("Hygiène +", __FILE__),

				//CLEANING ROBOT
				//ConsumerProducts.CleaningRobot.EnumType.ReferenceMapId
				'ReferenceMapId' => __("Identifiant de carte de référence", __FILE__),
				'TempMap' => __("Carte temporaire", __FILE__),
				'Map1' => __("Carte 1", __FILE__),
				'Map2' => __("Carte 2", __FILE__),
				'Map3' => __("Carte 3", __FILE__),
				'CurrentMap' => __("Carte actuelle", __FILE__),
				'NameOfMap1' => __("Nom de carte 1", __FILE__),
				'NameOfMap2' => __("Nom de carte 2", __FILE__),
				'NameOfMap3' => __("Nom de carte 3", __FILE__),
				'NameOfMap4' => __("Nom de carte 4", __FILE__),
				'NameOfMap5' => __("Nom de carte 5", __FILE__),
				//ConsumerProducts.CleaningRobot.EnumType.CleaningMode
				'CleaningMode' => __("Mode de nettoyage", __FILE__),
				'Silent' => __("Silencieux", __FILE__),
				'Power' => __("Puissant", __FILE__),
				'Standard' => __("Normal", __FILE__),
				//ConsumerProducts.CleaningRobot.Option.ProcessPhase
				'ProcessPhase' => __("Phase de traitement", __FILE__),
				//ConsumerProducts.CleaningRobot.Status
				'LastSelectedMap' => __("Dernière carte sélectionnée", __FILE__),
				'DustBoxInserted' => __("Boîte à poussière insérée", __FILE__),
				'Lost' => __("Perdu", __FILE__),
				'Lifted' => __("Levé", __FILE__),
				//ConsumerProducts.CleaningRobot.Event
				'EmptyDustBoxAndCleanFilter' => __("Vider la boîte à poussière et nettoyer le filtre", __FILE__),
				'RobotIsStuck' => __("Robot coincé", __FILE__),
				'DockingStationNotFound' => __("Station de recharge introuvable", __FILE__),
				//COFFEE MACHINE
				'CupWarmer' => __("Chauffe-tasse", __FILE__),
				//ConsumerProducts.CoffeeMaker.Status
				'BeverageCounterCoffee' => __("Nombre de cafés consommés", __FILE__),
				'BeverageCounterPowderCoffee' => __("Quantité de café en poudre consommée", __FILE__),
				'BeverageCounterHotWaterCups' => __("Nombre de tasses d'eau chaude consommés", __FILE__),
				'BeverageCounterHotMilk' => __("Quantité de lait chaud consommée", __FILE__),
				'BeverageCounterFrothyMilk' => __("Quantité de mousse de lait consommée", __FILE__),
				'BeverageCounterMilk' => __("Quantité de lait consommée", __FILE__),
				'BeverageCounterCoffeeAndMilk' => __("Quantité de café au lait consommée", __FILE__),
				'BeverageCounterHotWater' => __("Quantité d'eau chaude consommée", __FILE__),
				'BeverageCounterRistrettoEspresso' => __("Quantité de ristretto consommée", __FILE__),
				//ConsumerProducts.CoffeeMaker.EnumType.BeanAmount
				'BeanAmount' => __("Quantité de café", __FILE__),
				'VeryMild' => __("Très doux", __FILE__),
				'Mild' => __("Doux", __FILE__),
				'MildPlus' => __("Doux +", __FILE__),
				'Strong' => __("Fort", __FILE__),
				'StrongPlus' => __("Fort +", __FILE__),
				'VeryStrong' => __("Très fort", __FILE__),
				'VeryStrongPlus' => __("Très fort +", __FILE__),
				'ExtraStrong' => __("Extrèmement fort", __FILE__),
				'Normal' => __("Normal", __FILE__),
				'NormalPlus' => __("Normal +", __FILE__),
				'DoubleShot' => __("Double shot", __FILE__),
				'DoubleShotPlus' => __("Double shot +", __FILE__),
				'DoubleShotPlusPlus' => __("Double shot ++", __FILE__),
				'CoffeeGround' => __("Café moulu", __FILE__),
				//ConsumerProducts.CoffeeMaker.EnumType.CoffeeTemperature
				'CoffeeTemperature' => __("Température du café", __FILE__),
				'88C' => __("88°C", __FILE__),
				'90C' => __("90°C", __FILE__),
				'92C' => __("92°C", __FILE__),
				'94C' => __("94°C", __FILE__),
				'95C' => __("95°C", __FILE__),
				'96C' => __("96°C", __FILE__),
				//ConsumerProducts.CoffeeMaker.EnumType.BeanContainerSelection
				'BeanContainerSelection' => __("Sélecteur de récipient à grains", __FILE__),
				'Right' => __("Droite", __FILE__),
				'Left' => __("Gauche", __FILE__),
				//ConsumerProducts.CoffeeMaker.EnumType.FlowRate
				'FlowRate' => __("Débit", __FILE__),
				'Intense' => __("Intense", __FILE__),
				'IntensePlus' => __("Intense +", __FILE__),
				//'Normal' => __("Normal", __FILE__),
				'FillQuantity' => __("Contenance", __FILE__),
				//ConsumerProducts.CoffeeMaker.Program.Beverage
				'Ristretto' => __("Ristretto", __FILE__),
				'Espresso' => __("Espresso", __FILE__),
				'EspressoDoppio' => __("Double Espresso", __FILE__),
				'Coffee' => __("Café", __FILE__),
				'XLCoffee' => __("Café XL", __FILE__),
				'EspressoMacchiato' => __("Espresso macchiato", __FILE__),
				'Cappuccino' => __("Cappuccino", __FILE__),
				'LatteMacchiato' => __("Macchiato au lait", __FILE__),
				'CaffeLatte' => __("Café au lait", __FILE__),
				'MilkFroth' => __("Mousse de lait", __FILE__),
				'WarmMilk' => __("Lait chaud", __FILE__),
				//ConsumerProducts.CoffeeMaker.Program.CoffeeWorld
				'KleinerBrauner' => __("Petit café", __FILE__),
				'GrosserBrauner' => __("Grand café", __FILE__),
				'Verlaengerter' => __("Rallongé", __FILE__),
				'VerlaengerterBraun' => __("Café brun", __FILE__),
				'WienerMelange' => __("Mélange viennois", __FILE__),
				'FlatWhite' => __("Blanc pur", __FILE__),
				'Cortado' => __("Coupé", __FILE__),
				'CafeCortado' => __("Café coupé", __FILE__),
				'CafeConLeche' => __("Cafe con leche", __FILE__),
				'CafeAuLait' => __("Cafe Au Lait", __FILE__),
				'Doppio' => __("Double", __FILE__),
				'Kaapi' => __("Kaapi", __FILE__),
				'KoffieVerkeerd' => __("Mauvais café", __FILE__),
				'Galao' => __("Galao", __FILE__),
				'Garoto' => __("Garoto", __FILE__),
				'Americano' => __("American", __FILE__),
				'RedEye' => __("RedEye", __FILE__),
				'BlackEye' => __("BlackEye", __FILE__),
				'DeadEye' => __("DeadEye", __FILE__),
				//ConsumerProducts.CoffeeMaker.Event
				'BeanContainerEmpty' => __("Conteneur à grains vide", __FILE__),
				'WaterTankEmpty' => __("Réservoir d'eau vide", __FILE__),
				'DripTrayFull' => __("Bac de récupération plein", __FILE__),

				//DRYER
				//LaundryCare.Dryer.EnumType.DryingTarget
				'DryingTarget' => __("Cible de séchage", __FILE__),
				'IronDry' => __("Prêt à repasser", __FILE__),
				'CupboardDry' => __("Prêt à ranger", __FILE__),
				'CupboardDryPlus' => __("Prêt à ranger +", __FILE__),
				//LaundryCare.Dryer.Program
				'Cotton' => __("Coton", __FILE__),
				'Synthetic' => __("Synthétique", __FILE__),
				'Mix' => __("Mélangé", __FILE__),
				'Blankets' => __("Couvertures", __FILE__),
				'BusinessShirts' => __("Chemises de travail", __FILE__),
				'DownFeathers' => __("Plumes de duvet", __FILE__),
				'Hygiene' => __("Hygiénique", __FILE__),
				'Jeans' => __("Jeans", __FILE__),
				'Outdoor' => __("Extérieur", __FILE__),
				'SyntheticRefresh' => __("Rafraîchissement synthétique", __FILE__),
				'Towels' => __("Serviettes", __FILE__),
				'Delicates' => __("Délicat", __FILE__),
				'Super40' => __("Super 40°C", __FILE__),
				'Shirts15' => __("Chemises 15°C", __FILE__),
				'Pillow' => __("Oreiller", __FILE__),
				'AntiShrink' => __("Anti-rétrécissement", __FILE__),

				//WASHER
				//LaundryCare.Washer.EnumType.Temperature
				'Temperature' => __("Température", __FILE__),
				'Cold' => __("Froid", __FILE__),
				'GC20' => __("20°C", __FILE__),
				'GC30' => __("30°C", __FILE__),
				'GC40' => __("40°C", __FILE__),
				'GC50' => __("50°C", __FILE__),
				'GC60' => __("60°C", __FILE__),
				'GC70' => __("70°C", __FILE__),
				'GC80' => __("80°C", __FILE__),
				'GC90' => __("90°C", __FILE__),
				//LaundryCare.Washer.EnumType.SpinSpeed
				'SpinSpeed' => __("Essorage", __FILE__),
				'RPM400' => __("400 tr/min", __FILE__),
				'RPM600' => __("600 tr/min", __FILE__),
				'RPM800' => __("800 tr/min", __FILE__),
				'RPM1000' => __("1000 tr/min", __FILE__),
				'RPM1200' => __("1200 tr/min", __FILE__),
				'RPM1400' => __("1400 tr/min", __FILE__),
				'RPM700' => __("700 tr/min", __FILE__),
				'RPM900' => __("900 tr/min", __FILE__),
				'RPM1500' => __("1500 tr/min", __FILE__),
				'RPM1600' => __("1600 tr/min", __FILE__),
				'UlOff' => __("Sans essorage", __FILE__),
				'UlLow' => __("Vitesse d’essorage basse", __FILE__),
				'UlMedium' => __("Vitesse d’essorage moyenne", __FILE__),
				'UlHigh' => __("Vitesse d’essorage élevée", __FILE__),
				//LaundryCare.Washer.Program
				//'Cotton' => __("Coton", __FILE__),
				'EasyCare' => __("Synthétiques", __FILE__),
				//'Mix' => __("Mélangé", __FILE__),
				'DelicatesSilk' => __("Délicat Soie", __FILE__),
				'Wool' => __("Laine", __FILE__),
				'Sensitive' => __("Sensible", __FILE__),
				'Auto30' => __("Auto 30°C", __FILE__),
				'Auto40' => __("Auto 40°C", __FILE__),
				'Auto60' => __("Auto 60°C", __FILE__),
				'Chiffon' => __("Mousseline de soie", __FILE__),
				'Curtains' => __("Rideaux", __FILE__),
				'DarkWash' => __("Couleurs sombres", __FILE__),
				'Dessous' => __("Lingerie", __FILE__),
				'Monsoon' => __("Mousson", __FILE__),
				//'Outdoor' => __("Extérieur", __FILE__),
				'PlushToy' => __("Peluche", __FILE__),
				'ShirtsBlouses' => __("Chemises", __FILE__),
				'SportFitness' => __("Sport", __FILE__),
				//'Towels' => __("Serviettes", __FILE__),
				'WaterProof' => __("Imperméable", __FILE__),
				//LaundryCare.Washer.Option
				//'Temperature' => __("Température", __FILE__),
				//'SpinSpeed' => __("Essorage", __FILE__),
				'IDos1DosingLevel' => __("Dosage i-Dos de détergent", __FILE__),
				'IDos2DosingLevel' => __("i-DOS: dosage de lessive liquide ou d'adoucissant", __FILE__),
				'Prewash'  => __("Prélavage", __FILE__),
				'RinsePlus' => __("Rinçage +", __FILE__),
				'RinsePlus1' => __("Rinçage + 1", __FILE__),
				'RinseHold' => __("Arrêt cuve pleine", __FILE__),
				'Soak' => __("Tremper", __FILE__),
				'LoadRecommendation' => __("Recommandation de charges", __FILE__),
				'EnergyForecast' => __("Prévision d'énergie", __FILE__),
				'WaterForecast' => __("Prévision d'eau", __FILE__),
				//'DryingTarget' => __("Cible de séchage", __FILE__),
				'VentingLevel' => __("Niveau de ventilation", __FILE__),
				'LessIroning' => __("Moins de repassage", __FILE__),
				//LaundryCare.Common.Option
				'VarioPerfect' => __("VarioPerfect", __FILE__),
				//HOOD
				//Cooking.Common.EnumType.Hood.VentingLevel
				'FanOff' => __("Ventilateur éteint", __FILE__),
				'FanStage01' => __("Phase 1 de ventilation", __FILE__),
				'FanStage02' => __("Phase 2 de ventilation", __FILE__),
				'FanStage03' => __("Phase 3 de ventilation", __FILE__),
				//Cooking.Common.EnumType.Hood.IntensiveLevel
				'IntensiveLevel' => __("Niveau intensivité", __FILE__),
				'IntensiveStageOff' => __("Phase intensive arrêtée", __FILE__),
				'IntensiveStage1' => __("Phase intensive 1", __FILE__),
				'IntensiveStage2' => __("Phase intensive 2", __FILE__),
				//Cooking.Common.Setting
				'Lighting' => __("Éclairage", __FILE__),
				'LightingBrightnes' => __("Intensité de l'éclairage", __FILE__),

				//FRIDGEFREEZER
				//Refrigeration.FridgeFreezer.Setting
				'SetpointTemperatureFreezer' => __("Consigne température congélateur", __FILE__),
				'SetpointTemperatureRefrigerator' => __("Consigne température réfrigérateur", __FILE__),
				'SuperModeFreezer' => __("Super mode congélation", __FILE__),
				'SuperModeRefrigerator' => __("Super mode réfrigération", __FILE__),
				//Refrigeration.Common.Setting
				'EcoMode' => __("Mode éco", __FILE__),
				//'SabbathMode' => __("Mode Sabbat", __FILE__),
				'VacationMode' => __("Mode vacances", __FILE__),
				'FreshMode' => __("Mode frais", __FILE__),
				//Refrigeration.FridgeFreezer.Event
				'DoorAlarmFreezer' => __("Alarme de porte de congélateur", __FILE__),
				'DoorAlarmRefrigerator' => __("Alarme de porte de réfrigérateur", __FILE__),
				'TemperatureAlarmFreezer' => __("Alarme de température de congélateur", __FILE__),
				//WARMING DRAWER / OVEN
				//Cooking.Oven.EnumType.WarmingLevel
				'Low' => __("Bas", __FILE__),
				'Medium' => __("Moyen", __FILE__),
				'High' => __("Élevé", __FILE__),
				//Cooking.Oven.Program.HeatingMode
				'PreHeating' => __("Préchauffage", __FILE__),
				'HotAir' => __("Convection 3D", __FILE__),
				'HotAirEco' => __("Chaleur tournante éco", __FILE__),
				'HotAirGrilling' => __("Gril à convection", __FILE__),
				'TopBottomHeating' => __("Convection naturelle", __FILE__),
				'TopBottomHeatingEco' => __("Convection naturelle éco", __FILE__),
				'BottomHeating' => __("Résistance de sole", __FILE__),
				'PizzaSetting' => __("Pizza", __FILE__),
				'SlowCook' => __("Cuisson lente", __FILE__),
				'IntensiveHeat' => __("Chaleur intensive", __FILE__),
				'KeepWarm' => __("Maintien au chaud", __FILE__),
				'PreheatOvenware' => __("Préchauffer le four", __FILE__),
				'FrozenHeatupSpecial' => __("Réchauffage produit congelé", __FILE__),
				'Desiccation' => __("Déshydratation", __FILE__),
				'Defrost' => __("Décongélation", __FILE__),
				'Proof' => __("Levain", __FILE__),
				//Cooking.Oven.Option
				//'Duration' => __("Durée", __FILE__),
				'SetpointTemperature' => __("Consigne température", __FILE__),
				'FastPreHeat' => __("Préchauffage rapide", __FILE__),
				'WarmingLevel' => __("Niveau de chauffe", __FILE__),
				'SabbathMode' => __("Mode Sabbat", __FILE__),
				//Cooking.Oven.Status
				'CurrentCavityTemperature' => __("Température actuelle", __FILE__),
				//Cooking.Oven.Event
				'PreheatFinished' => __("Préchauffage terminé", __FILE__),
				//COMMON
				//BSH.Common.EnumType.PowerState
				'PowerState' => __("Statut de puissance", __FILE__),
				'TemperatureUnit' => __("Unité de température", __FILE__),
				'LiquidVolumeUnit' => __("Unité de volume de liquide", __FILE__),
				'AlarmClock' => __("Alarme", __FILE__),
				'ChildLock' => __("Sécurité enfants", __FILE__),
				'On' => __("Marche", __FILE__),
				'Off' => __("Arrêt", __FILE__),
				'Standby' => __("En attente", __FILE__),

				//BSH.Common.EnumType.OperationState
				'Ready' => __("Prêt", __FILE__),
				'Inactive' => __("Inactif", __FILE__),
				'Delayed Start' => __("Départ différé", __FILE__),
				'Pause' => __("Pause", __FILE__),
				'Run' => __("Marche", __FILE__),
				'Finished' => __("Terminé", __FILE__),
				'Error' => __("Erreur", __FILE__),
				'Action Required' => __("Action requise", __FILE__),
				'Aborting' => __("Abandon", __FILE__),
				'RemoteControlStartAllowed' => __("Démarrage à distance", __FILE__),
				'RemoteControlActive' => __("Contrôle à distance", __FILE__),
				'LocalControlActive' => __("Appareil en fonctionnement", __FILE__),
				'OperationState' => __("Statut de fonctionnement", __FILE__),

				//BSH.Common.EnumType.EventPresentState
				'EventPresentState' => __("État de l'évènement", __FILE__),
				'Present' => __("Présent", __FILE__),
				//'Off' => __("Désactivé", __FILE__),
				'Confirmed' => __("Confirmé", __FILE__),

				//BSH.Common.EnumType.DoorState
				'DoorState' => __("Porte", __FILE__),
				'Open' => __("Ouverte", __FILE__),
				'Closed' => __("Fermée", __FILE__),
				'Locked' => __("Verrouillée", __FILE__),
				//BSH.Common.Option
				'Duration' => __("Durée", __FILE__),
				'StartInRelative' => __("Départ différé", __FILE__),
				'FinishInRelative' => __("Fin différée", __FILE__),
				'ElapsedProgramTime' => __("Temps de programme écoulé", __FILE__),
				'RemainingProgramTime' => __("Temps de programme restant", __FILE__),
				'ProgramProgress' => __("Progression du programme", __FILE__),
				'ProgramFinished' => __("Fin du programme", __FILE__),
				'AmbientLightEnabled' => __("Lumière ambiante activée", __FILE__),
				'AmbientLightBrightness' => __("Intensité de la lumière ambiante", __FILE__),
				'AmbientLightColor' => __("Couleur de la lumière ambiante", __FILE__),
				'AmbientLightCustomColor' => __("Couleur personnalisée de la lumière ambiante", __FILE__),
				'BatteryLevel' => __("Niveau de batterie", __FILE__),
				'BatteryChargingState' => __("État de charge de la batterie", __FILE__),
				'ChargingConnection' => __("Connexion au chargeur", __FILE__),
				'CameraState' => __("État de la caméra", __FILE__),
				//BSH.Common.Event
				'ProgramAborted' => __("Programme annulé", __FILE__),
				'ProgramFinished' => __("Programme terminé", __FILE__),
				'AlarmClockElapsed' => __("Temps écoulé", __FILE__),
				//BSH.Common.Root
				'SelectedProgram' => __("Programme sélectionné", __FILE__),
				'ActiveProgram' => __("Programme en cours", __FILE__),
				//NOT DETERMINED YET
		];

		if (array_key_exists($word, $translate))  $word = $translate[$word];

		return $word;
	}

	public static function deleteEqLogic() {
		foreach (eqLogic::byType('homeconnect') as $eqLogic) {
			$eqLogic->remove();
		}
	}
	/*
	 * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom */
	/*public static function cron15() {
	  }
	*/

	/*
	 * Fonction exécutée automatiquement toutes les minutes par Jeedom */
	  public static function cron() {
		$autorefresh = config::byKey('autorefresh', 'homeconnect');
		if ($autorefresh != '') {
			try {
				$c = new Cron\CronExpression(checkAndFixCron($autorefresh), new Cron\FieldFactory);
				if ($c->isDue()) {
					log::add('homeconnect', 'debug', 'cron is due');
					self::updateAppliances();
				} else {
					self::verifyToken(180);
				}
			} catch (Exception $exc) {
                log::add('homeconnect', 'error', __("Erreur lors de l'exécution du cron ", __FILE__) . $exc->getMessage());
            }
		}
	  }
	/*
	 * Fonction exécutée automatiquement toutes les heures par Jeedom
	  public static function cronHourly() {

	  }
	 */


	public static function cronDaily() {
        cache::set('homeconnect::requests::total', 0, '');
        cache::set('homeconnect::requests::refresh_token', 0, '');
	}


	public static function setCmdName($_key, $_cmdData) {

		$nameNewTrans = self::getCmdNameTranslation($_key);
		if ($nameNewTrans) {
			return $nameNewTrans;
		} else if (array_key_exists('displayvalue', $_cmdData)) {
			return $_cmdData['displayvalue'];
		}
		return $_key;
	}

	/** *************************** Méthodes d'instance************************ */
	public function createActionCmd($cmdData, $path, $category) {
		$key = $cmdData['key'];
		log::add('homeconnect', 'debug', "Création d'une commande action key=" . $key . " path=" . $path . " category= " . $category);
		$logicalIdCmd = 'PUT::' . $key;
		$cmd = $this->getCmd(null, $logicalIdCmd);
		if (!is_object($cmd)) {
			// La commande n'existe pas, on la créée
			$cmd = new homeconnectCmd();
			$name = self::setCmdName($key, $cmdData);
			log::add('homeconnect', 'debug', "Nom de la nouvelle commande : " . $name);

			if ($this->cmdNameExists($name)) {
				$cmd->setName('Action ' . $name);
			} else {
				$cmd->setName($name);
			}
			$cmd->setLogicalId($logicalIdCmd);
			$cmd->setIsVisible(1);
			$cmd->setIsHistorized(0);
			// A voir en s'inspirant de homebridge homeconnect
			$cmd->setDisplay('generic_type', 'DONT');
			$cmd->setConfiguration('path', $path);
			$cmd->setConfiguration('key', $key);
			$cmd->setConfiguration('category', $category);
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('action');
			if ($cmdData['type'] == 'Int' || $cmdData['type'] == 'Double') {
				// commande slider.
				log::add('homeconnect', 'debug', "Nouvelle commande slider logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
				$cmd->setSubType('slider');
				$cmd->setConfiguration('value', '#slider#');
				if (isset($cmdData['unit'])) {
					$cmd->setConfiguration('unit', $cmdData['unit']);
					if ($cmdData['unit'] == 'seconds') {
						$cmd->setUnite('s');
					} else {
						$cmd->setUnite($cmdData['unit']);
					}

				} else {
					$cmd->setUnite('');
				}
				if (isset($cmdData['constraints']['min']) && isset($cmdData['constraints']['max'])) {
					$cmd->setConfiguration('minValue', $cmdData['constraints']['min']);
					$cmd->setConfiguration('maxValue', $cmdData['constraints']['max']);
				}
				log::add('homeconnect', 'debug', "Min = " . $cmd->getConfiguration('minValue') . " Max = " .$cmd->getConfiguration('maxValue') . " Unité = " . $cmd->getUnite());
				if ($cmd->getConfiguration('maxValue') < 1000) {
					$cmd->setTemplate('dashboard', 'button');
					$cmd->setTemplate('mobile', 'button');
				}
				/*else {
					$cmd->setTemplate('dashboard', 'bigbutton');
					$cmd->setTemplate('mobile', 'bigbutton');
				}*/
				$arr = $cmd->getDisplay('parameters');
				if (!is_array($arr)) {
					$arr = array();
				}
				if (isset($cmdData['constraints']['stepsize'])) {
					$cmd->setConfiguration('step', $cmdData['constraints']['stepsize']);
					$arr['step'] = $cmdData['constraints']['stepsize'];
				} else {
					$$arr['step'] = 1;
				}
				/*if ($cmd->getConfiguration('maxValue') >= 1000) {
						$arr['bigstep'] = 900;
				}*/
				$cmd->setDisplay('parameters', $arr);
				$cmd->save();
			} else if (strpos($cmdData['type'], 'EnumType') !== false) {
				// Commande select
				log::add('homeconnect', 'debug', "Nouvelle commande select logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
				$cmd->setSubType('select');
				$cmd->setConfiguration('value', '#select#');
				$optionValues = array();
				foreach ($cmdData['constraints']['allowedvalues'] as $optionValue) {
		            log::add('homeconnect', 'debug', "INFORMATION ne pas tenir compte cmdSelect= " .  self::getCmdValueTranslation($key, $optionValue));
					$optionValues[] = $optionValue . '|' . self::traduction(self::lastSegment('.', $optionValue));
				}
				$listValue = implode(';', $optionValues);
				$cmd->setConfiguration('listValue', $listValue);
				$cmd->save();
			} else if ($key == 'BSH.Common.Setting.AmbientLightCustomColor') {
				// Commande color
				log::add('homeconnect', 'debug', "Nouvelle commande color logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
				$cmd->setSubType('color');
				$cmd->setConfiguration('value', '#color#');
				$cmd->save();
			} else	{
				log::add('homeconnect', 'debug', "Nouvelle commande other logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
				$cmd->setSubType('other');
				if ($cmdData['type'] == 'Boolean') {
					$cmd->setConfiguration('value', true);
				}
				$cmd->save();
			}
		} else {
			log::add('homeconnect', 'debug', "La commande " . $logicalIdCmd . " et nom " . $cmd->getName() . " existe déjà" );
		}
		return $cmd;
	}

	public function createInfoCmd($cmdData, $path, $category, $actionCmd = null) {
		$key = $cmdData['key'];
		log::add('homeconnect', 'debug', "Création d'une commande info key=" . $key . " path=" . $path . " category= " . $category);
		$logicalIdCmd = 'GET::' . $key;
		$cmd = $this->getCmd(null, $logicalIdCmd);
		if (!is_object($cmd)) {
			// La commande n'existe pas, on la créée
			$cmd = new homeconnectCmd();
			$name = self::setCmdName($key, $cmdData);
			log::add('homeconnect', 'debug', "Nom de la nouvelle commande : " . $name);

			if ($this->cmdNameExists($name)) {
				$cmd->setName('Info ' . $name);
			} else {
				$cmd->setName($name);
			}
			log::add('homeconnect', 'debug', "Nouvelle commande info : logicalId " . $logicalIdCmd . ' et nom ' . $cmd->getName());
			$cmd->setLogicalId($logicalIdCmd);
			$cmd->setIsVisible(1);
			$cmd->setIsHistorized(0);
			// A voir en s'inspirant de homebridge homeconnect
			$cmd->setDisplay('generic_type', 'DONT');
			$cmd->setConfiguration('path', $path);
			$cmd->setConfiguration('key', $key);
			$cmd->setConfiguration('withAction', false);
			$cmd->setConfiguration('category', $category);
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('info');
			if (isset($actionCmd)) {
				// Il y aune commande action associée
				// On ne l'affiche pas
				$cmd->setIsVisible(0);
				$cmd->setConfiguration('withAction', true);
				// Détermination du subtype à partir de la commande action
				if ($actionCmd->getSubType() == 'slider') {
					// commande numeric.
					log::add('homeconnect', 'debug', "Création d'une commande info numeric à partir de la commande action");
					$cmd->setSubType('numeric');
					$cmd->setConfiguration('minValue', $actionCmd->getConfiguration('minValue', 0));
					$cmd->setConfiguration('maxValue', $actionCmd->getConfiguration('maxValue', 100));
					$cmd->setUnite($actionCmd->getUnite());
					log::add('homeconnect', 'debug', "Min = " . $cmd->getConfiguration('minValue') . " Max = " .$cmd->getConfiguration('maxValue') . " Unité = " . $cmd->getUnite());
					$cmd->save();
				} else if ($actionCmd->getSubType() == 'select') {
					// Commande string
					log::add('homeconnect', 'debug', "Création d'une commande info string à partir de la commande action");
					$cmd->setSubType('string');
					$cmd->save();
				} else if ($actionCmd->getSubType() == 'color') {
					// Commande color
					log::add('homeconnect', 'debug', "Création d'une commande info string à partir de la commande action");
					$cmd->setSubType('string');
					$cmd->save();
				} else if ($actionCmd->getSubType() == 'other') {
					if ($actionCmd->getConfiguration('value') === true) {
						// Commande binaire
						log::add('homeconnect', 'debug', "Création d'une commande info binary à partir de la commande action");
						$cmd->setSubType('binary');
					} else {
						// Commande string
						log::add('homeconnect', 'debug', "Création d'une commande info other à partir de la commande action");
						$cmd->setSubType('string');
					}
					$cmd->save();
				} else {
					log::add('homeconnect', 'debug', "Problème avec le subtype de la commande action associée " . $actionCmd->getSubType());
				}
				log::add('homeconnect', 'debug', "setValue sur la commande " . $category  . " " . $actionCmd->getLogicalId() . " commande info " .$cmd->getLogicalId());
				$actionCmd->setValue($cmd->getId());
				$actionCmd->save();
			} else if (isset($cmdData['type'])) {
				// Determination du subType a l'aide de l'étiquette type
				if ($cmdData['type'] == 'Int' || $cmdData['type'] == 'Double') {
					// commande numeric.
					log::add('homeconnect', 'debug', "Création d'une commande info numeric à partir de l'étiquette type");
					$cmd->setSubType('numeric');
					if (isset($cmdData['unit'])) {
						$cmd->setConfiguration('unit', $cmdData['unit']);
						if ($cmdData['unit'] == 'seconds') {
							$cmd->setUnite('s');
						} else {
							$cmd->setUnite($cmdData['unit']);
						}

					} else {
						$cmd->setUnite('');
					}
					if (isset($cmdData['constraints']['min']) && isset($cmdData['constraints']['max'])) {
						$cmd->setConfiguration('minValue', $cmdData['constraints']['min']);
						$cmd->setConfiguration('maxValue', $cmdData['constraints']['max']);
					}
					log::add('homeconnect', 'debug', "Min = " . $cmd->getConfiguration('minValue') . " Max = " .$cmd->getConfiguration('maxValue') . " Unité = " . $cmd->getUnite());
					$cmd->save();
				} else if (strpos($cmdData['type'], 'EnumType') !== false) {
					// Commande string
					log::add('homeconnect', 'debug', "Création d'une commande string à partir de l'étiquette type");
					$cmd->setSubType('string');
					$cmd->save();
				} else if ($cmdData['type'] == 'Boolean') {
					log::add('homeconnect', 'debug', "Création d'une commande binary à partir de l'étiquette type");
					$cmd->setSubType('binary');
					$cmd->save();
				}

			} else if (isset ($cmdData['value'])) {
				// détermination du subtype à partir de value
				if ($cmdData['value'] === true || $cmdData['value'] === false) {
					log::add('homeconnect', 'debug', "Création d'une commande binary à partir de la value");
					$cmd->setSubType('binary');
					$cmd->save();
				} else if ($cmdData['value'] === null || strpos($cmdData['value'], 'EnumType') !== false) {
					log::add('homeconnect', 'debug', "Création d'une commande string à partir de la value");
					$cmd->setSubType('string');
					$cmd->save();
				} else if (is_numeric($cmdData['value'])) {
					log::add('homeconnect', 'debug', "Création d'une commande numeric à partir de la value");
					$cmd->setSubType('numeric');
					if (isset($cmdData['unit'])) {
						$cmd->setConfiguration('unit', $cmdData['unit']);
						if ($cmdData['unit'] == 'seconds') {
							$cmd->setUnite('s');
							$cmd->setConfiguration('minValue', 0);
							$cmd->setConfiguration('maxValue', 86340);
						} else {
							$cmd->setUnite($cmdData['unit']);
						}
					} else {
						$cmd->setUnite('');
					}
					$cmd->save();
				} else {
					log::add('homeconnect', 'debug', "Impossible de trouver le subType à partir de value " . print_r($cmdData, true));
				}
			} else {
				log::add('homeconnect', 'debug', "Impossible de trouver le subType " . print_r($cmdData, true));
			}
		} else {
			log::add('homeconnect', 'debug', "La commande " . $logicalIdCmd . " et nom " . $cmd->getName() . " existe déjà" );
		}
		return $cmd;
	}

	public function createProgramOption($path, $optionData) {
		if (isset($optionData['key'])) {
			if ($optionData['key'] !== 'BSH.Common.Option.StartInRelative') {
				$optionPath = $path . '/options/' . $optionData['key'];
			} else {
				// Cette option ne peut pas être utilisée avec selected uniquement avec active
				$optionPath = 'programs/active/options/' . $optionData['key'];
			}
			$actionCmd = $this->createActionCmd($optionData, $optionPath, 'Option');
			$infoCmd = $this->createInfoCmd($optionData, $optionPath, 'Option', $actionCmd);
			// le setValue est fait dans createInfoCmd
		} else {
			log::add('homeconnect', 'debug', "Clé manquante dans une option de programme" );
		}
    }

	public function cmdNameExists($name) {
		$cleanName = substr(cleanComponanteName($name), 0, 127);
		foreach ($this->getCmd() as $liste_cmd) {
			if ($cleanName == $liste_cmd->getName()) {
				return true;
			}
		}
		return false;
	}

	public function getImage() {
		$filename = 'plugins/homeconnect/core/config/images/' . $this->getConfiguration('type') . '.png';
		if(file_exists(__DIR__.'/../../../../'.$filename)){
			return $filename;
		}
		return 'plugins/homeconnect/plugin_info/homeconnect_icon.png';
	}

	public function applyModuleConfiguration($_remove = false) {
		log::add('homeconnect', 'debug', __FUNCTION__ . " import de la configuration" );

		$this->setConfiguration('applyType', $this->getConfiguration('type'));
		$this->save();
		if ($this->getConfiguration('type') == '') {
		  return true;
		}
		$device = self::devicesParameters($this->getConfiguration('type'));
		if (!is_array($device)) {
			return true;
		}
		$this->import($device, $_remove);
	}

	public function preInsert() {

	}

	public function isConnected() {
		$cmdConnected = $this->getCmd('info', 'connected');
		if (is_object($cmdConnected)) {
			if ($this->getIsEnable() && $cmdConnected->execCmd()) {
				return true;
			} else {
				return false;
			}
		} else {
			log::add('homeconnect', 'debug', "[Erreur] La commande connected n'existe pas :");
			log::add('homeconnect', 'debug', "Type : " . $this->getConfiguration('type', ''));
			log::add('homeconnect', 'debug', "Marque : " . $this->getConfiguration('brand', ''));
			log::add('homeconnect', 'debug', "Modèle : " . $this->getConfiguration('vib', ''));
			log::add('homeconnect', 'debug', "Id : " . $this->getLogicalId());
		}
	}

	public function loadCmdFromConf($type) {
		log::add('homeconnect', 'debug',"Fonction loadCmdFromConf($type)");
		if (!is_file(dirname(__FILE__) . '/../config/types/' . $type . '.json')) {
			 log::add('homeconnect', 'debug', "Fichier introuvable : $type");
			return;
		}
		$content = file_get_contents(dirname(__FILE__) . '/../config/types/' . $type . '.json');
		if (!is_json($content)) {
			log::add('homeconnect', 'debug', "Pas un json : $type");
			return;
		}
		$device = json_decode($content, true);
		if (!is_array($device) || !isset($device['commands'])) {
			log::add('homeconnect', 'debug', "Pas un tableau ou aucune commande : $type");
			return true;
		}
		foreach ($device['commands'] as $command) {
			$cmd = null;
			foreach ($this->getCmd() as $liste_cmd) {
				if ((isset($command['logicalId']) && $liste_cmd->getLogicalId() == $command['logicalId'])
				|| (isset($command['name']) && $liste_cmd->getName() == $command['name'])) {
					$cmd = $liste_cmd;
					break;
				}
			}
			if ($cmd == null || !is_object($cmd)) {
				$cmd = new homeconnectCmd();
				$cmd->setEqLogic_id($this->getId());
				utils::a2o($cmd, $command);
				$cmd->save();
			}
		}
		event::add('jeedom::alert', array(
			'level' => 'warning',
			'page' => 'homeconnect',
			'message' => '',
		));
	}

	public function adjustProgramOptions($typeProgram, $programKey) {
		// Cette fonction est appelée quand il y a eu un changement de programme (actif ou sélectionné) et ajuste les options en fonction de ce programme
		log::add('homeconnect', 'debug', "Appel de la fonction adjustProgramOptions pour le type de programme $typeProgram clé $programKey");
		$programdata = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/programs/available/' . $programKey, null, 'GET', array());
		log::add('homeconnect', 'debug', "Résultat de la requête " . $programdata);
		$programdata = json_decode($programdata, true);
		if (isset($programdata['data']['options'])) {
			foreach($programdata['data']['options'] as $optionData) {
				if (isset($optionData['key'])) {
					$key = $optionData['key'];
					// Commande option action
					$logicalIdCmd = 'PUT::' . $key;
					log::add('homeconnect', 'debug', "Ajustement de la commande action " . $logicalIdCmd);
					$cmd = $this->getCmd('action', $logicalIdCmd);
					if (is_object($cmd)) {
						if ($cmd->getSubType() == 'slider') {
							// commande slider.
							log::add('homeconnect', 'debug', "Ajustement commande action slider logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
							if (isset($optionData['unit'])) {
								$cmd->setConfiguration('unit', $optionData['unit']);
								if ($optionData['unit'] == 'seconds') {
									$cmd->setUnite('s');
								} else {
									$cmd->setUnite($optionData['unit']);
								}

							} else {
								$cmd->setUnite('');
							}
							if (isset($optionData['constraints']['min']) && isset($optionData['constraints']['max'])) {
								$cmd->setConfiguration('minValue', $optionData['constraints']['min']);
								$cmd->setConfiguration('maxValue', $optionData['constraints']['max']);
							}
							log::add('homeconnect', 'debug', "Min = " . $cmd->getConfiguration('minValue') . " Max = " .$cmd->getConfiguration('maxValue') . " Unité = " . $cmd->getUnite());
							$arr = $cmd->getDisplay('parameters');
							if (!is_array($arr)) {
								$arr = array();
							}
							if (isset($optionData['constraints']['stepsize'])) {
								$cmd->setConfiguration('step', $optionData['constraints']['stepsize']);
								$arr['step'] = $optionData['constraints']['stepsize'];
							} else {
								$$arr['step'] = 1;
							}
							/*if ($cmd->getConfiguration('maxValue') >= 1000) {
									$arr['bigstep'] = 900;
							}*/
							$cmd->setDisplay('parameters', $arr);
							$cmd->save();
						} else if ($cmd->getSubType() == 'select') {
							// Commande select
							log::add('homeconnect', 'debug', "Ajustement commande action select logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
							$optionValues = array();
							foreach ($optionData['constraints']['allowedvalues'] as $optionValue) {
		                        log::add('homeconnect', 'debug', "INFORMATION ne pas tenir compte cmdSelect= " .  self::getCmdValueTranslation($key, $optionValue));
								$optionValues[] = $optionValue . '|' . self::traduction(self::lastSegment('.', $optionValue));
							}
							$listValue = implode(';', $optionValues);
							log::add('homeconnect', 'debug', "listValue " . $listValue);
							$cmd->setConfiguration('listValue', $listValue);
							$cmd->save();
						} else {
							log::add('homeconnect', 'debug', "Commande action other rien à ajuster " . $logicalIdCmd . " nom ". $cmd->getName() . ' subtype ' . $cmd->getSubType());
						}
					} else {
						log::add('homeconnect', 'debug', "La commande action " . $logicalIdCmd . " n'existe pas impossible de l'ajuster" );
					}
					// commande option info
					$logicalIdCmd = 'GET::' . $key;
					log::add('homeconnect', 'debug', "Ajustement de la commande info " . $logicalIdCmd);
					$cmd = $this->getCmd(null, $logicalIdCmd);
					if (is_object($cmd)) {
						if ($cmd->getSubType() == 'numeric') {
							// commande numeric.
							log::add('homeconnect', 'debug', "Ajustement commande info numeric logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
							if (isset($optionData['unit'])) {
								$cmd->setConfiguration('unit', $optionData['unit']);
								if ($optionData['unit'] == 'seconds') {
									$cmd->setUnite('s');
								} else {
									$cmd->setUnite($optionData['unit']);
								}
							} else {
								$cmd->setUnite('');
							}
							if (isset($optionData['constraints']['min']) && isset($optionData['constraints']['max'])) {
								$cmd->setConfiguration('minValue', $optionData['constraints']['min']);
								$cmd->setConfiguration('maxValue', $optionData['constraints']['max']);
							}
							log::add('homeconnect', 'debug', "Min = " . $cmd->getConfiguration('minValue') . " Max = " .$cmd->getConfiguration('maxValue') . " Unité = " . $cmd->getUnite());
							$cmd->save();
						} else {
							// Dans les autres cas il n'y a rien à faire.
							log::add('homeconnect', 'debug', "Rien à ajuster pour une commande info de subType " . $cmd->getSubType());
						}
					} else {
						log::add('homeconnect', 'debug', "La commande info " . $logicalIdCmd . " n'existe pas impossible de l'ajuster" );
					}
				} else {
					log::add('homeconnect', 'debug', "Pas de key dans optionData" );
				}
			}
		} else {
			log::add('homeconnect', 'debug', "Pas d'options à ajuster" );
		}
	}

	public function updateInfoCmdValue($logicalId, $value) {

		$parts = explode('::', $logicalId);
		$cmd = $this->getCmd('info', $logicalId);
		$reglage = '';
		if (is_object($cmd)) {
			if (is_bool($value['value'])) {
				$value['value'] = $value['value']  ? 'true' : 'false';
			}
			if ($cmd->getConfiguration('withAction')) {
				// C'est une commande associée à une commande action pas de traduction
				if (isset($value['value'])) {
					$reglage = $value['value'];
				} else {
					log::add('homeconnect', 'debug', "La commande info : ".$logicalId." n'a pas de valeur");
				}
			} else {
					if (isset($value['value'])) {
						if ($cmd->getSubType() == 'string') {
		                    log::add('homeconnect', 'debug', "INFORMATION ne pas tenir compte cmdValue= " . self::getCmdValueTranslation($parts[1], $value['value']));
							$reglage = self::traduction(self::lastSegment('.', $value['value']));
						} else {
							$reglage = $value['value'];
						}
					} else {
						log::add('homeconnect', 'debug', "la commande info : ".$logicalId." n'a pas de valeur");
					}
				}
			$this->checkAndUpdateCmd($cmd, $reglage);
			log::add('homeconnect', 'debug', "Mise à jour setting : ".$logicalId." - Valeur :".$reglage);
		} else {
			log::add('homeconnect', 'debug', "Dans updateInfoCmdValue la commande : ".$logicalId." n'existe pas");
		}
	}

    public function lookProgram($programType) {
		if ($programType == 'Selected') {
			$nameCmd = 'GET::BSH.Common.Root.SelectedProgram';
		} else {
			$nameCmd = 'GET::BSH.Common.Root.ActiveProgram';
		}
		$currentProgram = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/programs/' . strtolower($programType), null, 'GET', array());
		if ($currentProgram !== false) {
			log::add('homeconnect', 'debug', __FUNCTION__ . "Réponse pour program $programType dans lookProgram " . $currentProgram);
			$currentProgram = json_decode($currentProgram, true);
			if (isset($currentProgram['data']['key']) && $currentProgram['data']['key'] !== 'SDK.Error.NoProgram' . $programType) {
				$key = $currentProgram['data']['key'];
				log::add('homeconnect', 'debug', __FUNCTION__ . "Program $programType key = " . $key);
				// recherche du programme action associé

				$actionCmd = $this->getCmd('action', 'PUT::' . $key);
				if (!is_object($actionCmd)) {
					log::add('homeconnect', 'debug', __FUNCTION__ . " Nouveau program $programType key = " . $key);
					$this->lookProgramAvailable($programType, $currentProgram['data']);
					log::add('homeconnect', 'debug', __FUNCTION__ . " Pas de commande action " . 'PUT::' . $key);
		            log::add('homeconnect', 'debug', "INFORMATION ne pas tenir compte lookProgram= " .  self::getCmdNameTranslation($key));
					$programName = self::traduction(self::lastSegment('.', $key));
				} else {
					$programName = $actionCmd->getName();
					log::add('homeconnect', 'debug', __FUNCTION__ . " Nom de la commande action " . $programName);
				}
				// MAJ de la commande info ProgramSelected ou ProgramActive.
				$cmd = $this->getCmd('info', $nameCmd);
				if (is_object($cmd)) {
					log::add('homeconnect', 'debug', "Mise à jour de la valeur de la commande action $nameCmd = ".$programName);
					$this->checkAndUpdateCmd($cmd, $programName);
					return true;
				} else {
					log::add('homeconnect', 'debug', "La commande $nameCmd n'existe pas :");
				}
				//recherche des options ce program pour ajout cmd option
				$this->lookProgramOptions($programType);
			} else {
				// Pas de programme actif
				// A voir : mettre à jour les autres commandes (états et réglages)
				log::add('homeconnect', 'debug', "pas de key ou key = SDK.Error.NoProgram" . $programType);
				$this->checkAndUpdateCmd($nameCmd, __("Aucun", __FILE__));
			}
		} else {
			log::add('homeconnect', 'debug', "Dans lookProgram request a retourné faux");
		}
		return false;
	}

	public function lookProgramAvailable($programType, $applianceProgram) {

		$programdata = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/programs/available/' . $applianceProgram['key'], null, 'GET', array());
		log::add('homeconnect','debug', 'Appliance Program available' . print_r($programdata, true));
		if ($programdata !== false && $programdata !== 'SDK.Error.UnsupportedProgram') {
			$programdata = json_decode($programdata, true);

			if (isset($programdata['data']['key'])) {
				$actionCmd = $this->createActionCmd($programdata['data'], 'programs/' . strtolower($programType), 'Program');
				if ($programType == 'Selected' || $programType == 'Active') {
					$infoCmd = $this->getCmd('info', 'GET::BSH.Common.Root.' . $programType . 'Program');
					if (is_object($infoCmd)) {
						// On a trouvé la commande info associée.
						log::add('homeconnect', 'debug', __FUNCTION__ . "setValue sur la commande programme $programType " . $actionCmd->getLogicalId() . " commande info " .$infoCmd->getLogicalId());
						$actionCmd->setValue($infoCmd->getId());
						$actionCmd->save();
					} else {
						log::add('homeconnect', 'debug', __FUNCTION__ . 'Pas de commande info GET::BSH.Common.Root.' . $programType . 'Program');
					}
				}
			}
		}
	}

	public function lookProgramOptions($programType) {
		$programOptions = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/programs/' . strtolower($programType) .'/options', null, 'GET', array());
		if ($programOptions !== false) {
			$programOptions = json_decode($programOptions, true);
			if (isset($programOptions['data']['key']) && $programOptions['data']['key'] !== 'SDK.Error.UnsupportedProgram') {
				log::add('homeconnect', 'debug', "options : " . $programOptions);
				$logicalId = 'GET::' . $value['key'];
				// MAJ des options et autres informations du programme en cours.
				foreach ($programOptions['data']['options'] as $value) {
					log::add('homeconnect', 'debug', "option : " . print_r($value, true));

					$this->createProgramOption('programs/' . strtolower($programType) , $value);
					//$this->createInfoCmd($value, $optionPath, 'Option');
					$this->updateInfoCmdValue($logicalId, $value);
				}
			}
		}
	}

	public function updateProgram() {
		if ($this->isConnected()) {
			$eqLogicType = $this->getConfiguration('type');
			if ($eqLogicType == 'Refrigerator' || $eqLogicType == 'FridgeFreezer' || $eqLogicType == 'WineCooler' || !$this->getConfiguration('hasPrograms', true)) {
				log::add('homeconnect', 'debug', "Pas de programme pour ce type d'appareil");
				return;
			}
			log::add('homeconnect', 'debug', "MAJ du programme actif");
            if ($this->lookProgram('Active')) {
				// Il y a un programme actif on regarde ses options
				log::add('homeconnect', 'debug', "Il y a un programme actif");
				$this->lookProgramOptions('Active');
			} else {
				// Pas de programme actif on essaie le programme sélectionné
				if ($this->lookProgram('Selected')) {
					log::add('homeconnect', 'debug', "i y a un programme sélectionné");
					$this->lookProgramOptions('Selected');
				}
			}
		}
	}

    public static function deamon_info() {
        //log::add('homeconnect', 'info', 'Etat du deamon homeconnectd');

        $return = array();
        $return['log'] = 'homeconnect';
        $return['state'] = 'nok';
        $pid = trim( shell_exec ('ps ax | grep "/homeconnectd.php" | grep -v "grep" | wc -l') );
        if ($pid != '' && $pid != '0') {
            $return['state'] = 'ok';
        }
        if (config::byKey('client_id','homeconnect','') != '' && config::byKey('client_secret','homeconnect','') != '') {
            $return['launchable'] = 'ok';
        } else{
            $return['launchable'] = 'nok';
            $return['launchable_message'] = __('Le client ou la clé ne sont pas configurés.', __FILE__);
        }
        //log::add('homeconnect', 'info', 'Statut : ' . $return['state']);
        return $return;
    }

  /**********************/

    public static function deamon_start($_debug = false) {
        log::add('homeconnect', 'info', 'Lancement du service homeconnect');
        $deamon_info = self::deamon_info();
        if ($deamon_info['launchable'] != 'ok') {
            throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
        }
        if ($deamon_info['state'] == 'ok') {
            self::deamon_stop();
            sleep(2);
        }
        log::add('homeconnectd', 'info', 'Lancement du démon homeconnect');
        $cmd = substr(dirname(__FILE__),0,strpos (dirname(__FILE__),'/core/class')).'/resources/homeconnectd.php';
        log::add('homeconnectd', 'debug', 'Deamon cmd : ' . $cmd);

        $result = exec('sudo php ' . $cmd . ' >> ' . log::getPathToLog('homeconnectd') . ' 2>&1 &');
        if (strpos(strtolower($result), 'error') !== false || strpos(strtolower($result), 'traceback') !== false) {
            log::add('homeconnectd', 'error', 'Deamon error : ' . $result);
            return false;
        }
        sleep(1);
        $i = 0;
        while ($i < 30) {
            $deamon_info = self::deamon_info();
            if ($deamon_info['state'] == 'ok') {
                break;
            }
            sleep(1);
            $i++;
        }
        if ($i >= 30) {
            log::add('homeconnectd', 'error', 'Impossible de lancer le démon homeconnectd', 'unableStartDeamon');
            return false;
        }
        log::add('homeconnectd', 'info', 'Démon homeconnectd lancé');
        return true;
    }

  /**********************/

    public static function deamon_stop() {
        log::add('homeconnectd', 'info', 'Arrêt du service homeconnect');
        $cmd='/homeconnectd.php';
        exec('sudo kill -9 $(ps aux | grep "'.$cmd.'" | awk \'{print $2}\')');
        sleep(1);
        exec('sudo kill -9 $(ps aux | grep "'.$cmd.'" | awk \'{print $2}\')');
        sleep(1);
        $deamon_info = self::deamon_info();
        if ($deamon_info['state'] == 'ok') {
            exec('sudo kill -9 $(ps aux | grep "'.$cmd.'" | awk \'{print $2}\')');
            sleep(1);
        } else {
            return true;
        }
        $deamon_info = self::deamon_info();
        if ($deamon_info['state'] == 'ok') {
            exec('sudo kill -9 $(ps aux | grep "'.$cmd.'" | awk \'{print $2}\')');
            sleep(1);
            return true;
        }
    }

	public function updateStates() {
		if ($this->isConnected()) {
			log::add('homeconnect', 'debug', "MAJ des états ".$this->getLogicalId());

			$response = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/status', null, 'GET', array());
			log::add('homeconnect', 'debug', "Réponse dans updateStates : " . $response);
			if ($response !== false) {
				$response = json_decode($response, true);
				foreach($response['data']['status'] as $value) {
					log::add('homeconnect', 'debug', "status : " . print_r($value, true));
					// Récupération du logicalId du status.
					$logicalId = 'GET::' .$value['key'];
					$cmd = $this->getCmd('info', $logicalId);
					if (!is_object($cmd)) {
						$this->createInfoCmd($value, 'status/' . $value['key'], 'Status');
					}
					$this->updateInfoCmdValue($logicalId, $value);
				}
			}
		} else {
			log::add('homeconnect', 'debug', "Non connecté, pas de mise à jour des états");
		}
	}

	public function updateSettings() {
		if ($this->isConnected()) {
			log::add('homeconnect', 'debug', "MAJ des réglages ".$this->getLogicalId());

			$response = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/settings', null, 'GET', array());
			log::add('homeconnect', 'debug', "Réponse updateSettings : " . $response);
			if ($response !== false) {
				$response = json_decode($response, true);
				foreach($response['data']['settings'] as $value) {
					log::add('homeconnect', 'debug', "setting : " . print_r($value, true));
					// Récupération du logicalId du setting.
					$logicalId = 'GET::' . $value['key'];
					$cmd = $this->getCmd('info', $logicalId);
					if (!is_object($cmd)) {
						$this->createInfoCmd($value, 'settings/' . $value['key'], 'Setting');
					}
					$this->updateInfoCmdValue($logicalId, $value);
				}
			}
		} else {
			log::add('homeconnect', 'debug', "Non connecté, pas de mise à jour des états");
		}
	}

	public function updateApplianceData() {
		log::add('homeconnect', 'debug',"Fonction updateApplianceData()");
		if ($this->getIsEnable()){
			log::add('homeconnect', 'debug',"Mise à jour du status connecté");
			$response = self::request(self::API_REQUEST_URL, null, 'GET', array());
			$response = json_decode($response, true);
			foreach($response['data']['homeappliances'] as $appliance) {
				log::add('homeconnect', 'debug',"Appareil " . print_r($appliance, true));
				if ($this->getLogicalId() == $appliance['haId']) {
					$cmd = $this->getCmd('info', 'connected');
					if (is_object($cmd)) {
						log::add('homeconnect', 'debug',"Mise à jour commande connectée valeur " . $appliance['connected']);
						$this->checkAndUpdateCmd($cmd, $appliance['connected']);
					}
				}
			}
			$this->updateProgram();
			$this->updateStates();
			$this->updateSettings();
			$this->refreshWidget();
		}
	}

	public function postInsert() {

	}

	public function preSave() {

	}

	public function postSave() {
	/**
	 * Création / MAJ des commandes des appareils.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */
		log::add('homeconnect', 'debug', __FUNCTION__ . " début");

		if ($this->getConfiguration('applyType') != $this->getConfiguration('type')) {
			$this->applyModuleConfiguration();
			//A voir : Supprimer toutes les commandes ici
			$this->refreshWidget();
		}
		//Parce qu'elles sont de toute façon mises à jour ici.
		$this->loadCmdFromConf($this->getConfiguration('type'));
		log::add('homeconnect', 'debug', __FUNCTION__ . " fin");
	}

	public function preUpdate() {

	}

	public function postUpdate() {

	}

	public function preRemove() {

	}

	public function postRemove() {

	}

	/*
	 * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
	  public function toHtml($_version = 'dashboard') {

	  }
	 */



	/** *************************** Getters ********************************* */



	/** *************************** Setters ********************************* */



}

class homeconnectCmd extends cmd {

	/** *************************** Constantes ******************************** */



	/** *************************** Attributs ********************************* */



	/** *************************** Attributs statiques *********************** */



	/** *************************** Méthodes ********************************** */



	/** *************************** Méthodes statiques ************************ */



	/** *************************** Méthodes d'instance************************ */

	/*
	 * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
	  public function dontRemoveCmd() {
	  return true;
	  }
	 */

	public function execute($_options = array()) {
		// Bien penser dans les fichiers json à mettre dans la configuration
		// key, value, type, constraints et à modifier findProduct
		log::add('homeconnect', 'debug',"Fonction execute()");
		homeconnect::verifyToken(60);

		if ($this->getType() == 'info') {
			log::add('homeconnect', 'debug',"Pas d'execute pour une commande info");
			return;
		}
		$eqLogic = $this->getEqLogic();
		$haid = $eqLogic->getConfiguration('haid', '');
		log::add('homeconnect', 'debug',"logicalId : " . $this->getLogicalId());
		log::add('homeconnect', 'debug',"Options : " . print_r($_options, true));

		if ($this->getLogicalId() == 'DELETE::StopActiveProgram') {
			// Commande Arrêter
			log::add('homeconnect', 'debug',"Commande arrêter");
			// Si l'appareil n'a pas de programme on ne peut pas arrêter
			if (!$eqLogic->getConfiguration('hasPrograms', true)) {
				log::add('homeconnect', 'debug',"L'appareil n'a pas de programmes impossible d'arrêter");
				return;
			}
			// S'il n'y a pas de programme actif on ne peut pas arrêter
			$response = homeconnect::request(homeconnect::API_REQUEST_URL . '/' . $haid . '/programs/active', null, 'GET', array());
			if ($response == false || $response == 'SDK.Error.NoProgramActive') {
				log::add('homeconnect', 'debug',"Pas de programme actif impossible d'arrêter");
				return;
			}
		}
		// Pour la commande arrêter le traitement continue

		if ($this->getLogicalId() == 'start') {
			// Commande Lancer
			log::add('homeconnect', 'debug',"Commande lancer");
			// Si l'appareil n'a pas de programme on ne peut pas lancer
			if (!$eqLogic->getConfiguration('hasPrograms', true)) {
				log::add('homeconnect', 'debug',"L'appareil n'a pas de programmes, impossible de lancer");
				return;
			}

			// On lance le programme sélectionné à condition qu'il existe
			log::add('homeconnect', 'debug',"Recherche du programme sélectionné");
			$response = homeconnect::request(homeconnect::API_REQUEST_URL . '/' . $haid . '/programs/selected', null, 'GET', array());
			log::add('homeconnect', 'debug',"Réponse du serveur pour le programme sélectionné " . $response);
			if ($response == false) {
				log::add('homeconnect', 'debug',"Pas de programme sélectionné impossible de lancer");
				event::add('jeedom::alert', array(
					'level' => 'warning',
					'message' => __("Sélectionnez un programme avant de lancer", __FILE__),
				));
				return;
			}
			$decodedResponse = json_decode($response, true);
			if(!isset($decodedResponse['data']['key'])) {
				log::add('homeconnect', 'debug',"Pas de programme dans la réponse impossible de lancer");
				return;
			}
			$key = $decodedResponse['data']['key'];
			$selectedProgramCmd = $eqLogic->getCmd(null, 'PUT::' . $key);
			if (!is_object($selectedProgramCmd)) {
				// Commande pour le programme sélectionné non trouvée
				log::add('homeconnect', 'debug',"La commande logicalId " . 'PUT::' . $key . " n'existe pas impossible de lancer");
				return;
			}
			// Si ce n'est pas un programme selectandstart impossible de lancer
			if ($selectedProgramCmd->getConfiguration('path', '') !== 'programs/selected') {
				log::add('homeconnect', 'debug',"Le programme sélectionné n'est pas select and start, impossible de lancer");
				return;
			}
			$url = homeconnect::API_REQUEST_URL . '/'. $haid . '/programs/active';
			$payload = '{"data": {"key": "' . $key . '"';

			// Il faut récupérer la valeur du départ différé et la mettre dans le payload.
			$cache = cache::byKey('homeconnect::startinrelative::'.$eqLogic->getId());
			$startinrelative = $cache->getValue();
			if ($startinrelative !== '' && $startinrelative !== 0) {
				$payload .= ',"options": [' . $startinrelative . ']';
			}
			$payload .= '}}';
			log::add('homeconnect', 'debug',"url pour le lancement " . $url);
			log::add('homeconnect', 'debug',"payload pour le lancement " . $payload);
			$result = homeconnect::request($url, $payload, 'PUT', array());
			log::add('homeconnect', 'debug',"Réponse du serveur au lancement " . $result);
			$eqLogic->updateApplianceData();
			return;

		}
		if ($this->getLogicalId() == 'refresh') {
			log::add('homeconnect', 'debug',"| Commande refresh");
			$eqLogic->updateApplianceData();
			return;
		}
		log::add('homeconnect', 'debug',"| Commande générique");
		$parts = explode('::', $this->getLogicalId());
		if (count($parts) !== 2) {
			log::add('homeconnect', 'debug',"Wrong number of parts in command eqLogic");
			return;
		}
		$method = $parts[0];
		$key = $parts[1];
		// A voir : faut il ajouter qqchose aux headers par defaut de request
		$headers = array();


		// Bien penser à mettre la partie après haid de l'url dans configuration path de la commande
		$path = $this->getConfiguration('path', '');
		$replace = array();
		switch ($this->getSubType()) {
			case 'slider':
			$replace['#slider#'] = intval($_options['slider']);
			break;
			case 'color':
			$replace['#color#'] = $_options['color'];
			break;
			case 'select':
			$replace['#select#'] = $_options['select'];
			break;
			case 'message':
			$replace['#title#'] = $_options['title'];
			$replace['#message#'] = $_options['message'];
			if ($_options['message'] == '' && $_options['title'] == '') {
			  throw new Exception(__('Le message et le sujet ne peuvent pas être vide', __FILE__));
			}
			break;
		}

		if ($method == 'DELETE') {
			$payload = null;
		} else {
			if ($this->getLogicalId() !== 'PUT::BSH.Common.Option.StartInRelative') {
				// La commande départ différé doit être envoyée au moment du lancer de programme.
				$parameters = array('data' => array());
				if ($this->getConfiguration('key') !== '') {
					$parameters['data']['key'] = $this->getConfiguration('key', '');
				}
				if ($this->getConfiguration('value') !== '') {
					if ($this->getConfiguration('value') === true || $this->getConfiguration('value') === false) {
						$parameters['data']['value'] = $this->getConfiguration('value');
					} else {
						$parameters['data']['value'] = str_replace(array_keys($replace),$replace,$this->getConfiguration('value', ''));
					}
				}

				if ($this->getConfiguration('unit', '') !== '') {
					$parameters['data']['unit'] = $this->getConfiguration('unit', '');
				}
				if ($this->getConfiguration('type', '') !== '') {
					$parameters['data']['type'] = $this->getConfiguration('type', '');
				}
				$payload = json_encode($parameters);

				$url = homeconnect::API_REQUEST_URL . '/'. $haid . '/' . $path;
				log::add('homeconnect', 'debug',"Paramètres de la requête pour exécuter la commande :");
				log::add('homeconnect', 'debug',"Method : " . $method);
				log::add('homeconnect', 'debug',"Url : " . $url);
				log::add('homeconnect', 'debug',"Payload : " . $payload);
				$response = homeconnect::request($url, $payload, $method, $headers);
				log::add('homeconnect', 'debug',"Réponse du serveur : " . $response);
				// si la requête est de category program il faut mettre à jour les options
				if ($this->getConfiguration('category') == 'Program') {
					$typeProgram = homeconnect::lastSegment('/', $url);
					$eqLogic->adjustProgramOptions($typeProgram, $this->getConfiguration('key'));
					// A voir dans ce cas ce qu'il faut mettre à jour.
				}
				$eqLogic->updateApplianceData();
			} else {
				$value = str_replace(array_keys($replace),$replace,$this->getConfiguration('value', ''));
				$payload = '{"key":"BSH.Common.Option.StartInRelative","value":' . $value. ',"unit":"seconds"}';
				cache::set('homeconnect::startinrelative::'.$eqLogic->getId(), $payload, '');
				// il faut mémoriser la valeur du départ différé.
			}
		}
	}

	/** *************************** Getters ********************************* */



	/** *************************** Setters ********************************* */



}
?>
