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

class homeconnect_capabilities {

    public static $appliancesList;
    public static $appliancesCapabilities;
  
    public function __construct()
    {
        $this->appliancesList = [
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
        ];

      	/**
         * Liste toutes les clés Home connect, les valeurs, type...
         *
         * @param			|*Cette fonction ne retourne pas de valeur*|
         * @return	$KEYS		array		Tableau de toutes les clés
         */
        $this->appliancesCapabilities = [
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
										'BSH.Common.EnumType.AmbientLightColor.Color1' => array(
										    'name' => __("Couleur 1", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color2' => array(
										    'name' => __("Couleur 2", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color3' => array(
										    'name' => __("Couleur 3", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color4' => array(
										    'name' => __("Couleur 4", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color5' => array(
										    'name' => __("Couleur 5", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color6' => array(
										    'name' => __("Couleur 6", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color7' => array(
										    'name' => __("Couleur 7", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color8' => array(
										    'name' => __("Couleur 8", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color9' => array(
										    'name' => __("Couleur 9", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color10' => array(
										    'name' => __("Couleur 10", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color11' => array(
										    'name' => __("Couleur 11", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color12' => array(
										    'name' => __("Couleur 12", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color13' => array(
										    'name' => __("Couleur 13", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color14' => array(
										    'name' => __("Couleur 14", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color15' => array(
										    'name' => __("Couleur 15", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color16' => array(
										    'name' => __("Couleur 16", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color17' => array(
										    'name' => __("Couleur 17", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color18' => array(
										    'name' => __("Couleur 18", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color19' => array(
										    'name' => __("Couleur 19", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color20' => array(
										    'name' => __("Couleur 20", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color21' => array(
										    'name' => __("Couleur 21", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color22' => array(
										    'name' => __("Couleur 22", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color23' => array(
										    'name' => __("Couleur 23", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color24' => array(
										    'name' => __("Couleur 24", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color25' => array(
										    'name' => __("Couleur 25", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color26' => array(
										    'name' => __("Couleur 26", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color27' => array(
										    'name' => __("Couleur 27", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color28' => array(
										    'name' => __("Couleur 28", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color29' => array(
										    'name' => __("Couleur 29", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color30' => array(
										    'name' => __("Couleur 30", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color31' => array(
										    'name' => __("Couleur 31", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color32' => array(
										    'name' => __("Couleur 32", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color33' => array(
										    'name' => __("Couleur 33", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color14' => array(
										    'name' => __("Couleur 34", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color15' => array(
										    'name' => __("Couleur 35", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color36' => array(
										    'name' => __("Couleur 36", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color37' => array(
										    'name' => __("Couleur 37", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color38' => array(
										    'name' => __("Couleur 38", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color39' => array(
										    'name' => __("Couleur 39", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color40' => array(
										    'name' => __("Couleur 40", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color41' => array(
										    'name' => __("Couleur 41", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color42' => array(
										    'name' => __("Couleur 42", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color43' => array(
										    'name' => __("Couleur 43", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color44' => array(
										    'name' => __("Couleur 44", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color45' => array(
										    'name' => __("Couleur 45", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color46' => array(
										    'name' => __("Couleur 46", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color47' => array(
										    'name' => __("Couleur 47", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color48' => array(
										    'name' => __("Couleur 48", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color49' => array(
										    'name' => __("Couleur 49", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color50' => array(
										    'name' => __("Couleur 50", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color51' => array(
										    'name' => __("Couleur 51", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color52' => array(
										    'name' => __("Couleur 52", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color53' => array(
										    'name' => __("Couleur 53", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color54' => array(
										    'name' => __("Couleur 54", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color55' => array(
										    'name' => __("Couleur 55", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color56' => array(
										    'name' => __("Couleur 56", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color57' => array(
										    'name' => __("Couleur 57", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color58' => array(
										    'name' => __("Couleur 58", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color59' => array(
										    'name' => __("Couleur 59", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color60' => array(
										    'name' => __("Couleur 60", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color61' => array(
										    'name' => __("Couleur 61", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color62' => array(
										    'name' => __("Couleur 62", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color63' => array(
										    'name' => __("Couleur 63", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color64' => array(
										    'name' => __("Couleur 64", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color65' => array(
										    'name' => __("Couleur 65", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color66' => array(
										    'name' => __("Couleur 66", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color67' => array(
										    'name' => __("Couleur 67", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color68' => array(
										    'name' => __("Couleur 68", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color69' => array(
										    'name' => __("Couleur 69", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color70' => array(
										    'name' => __("Couleur 70", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color71' => array(
										    'name' => __("Couleur 71", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color72' => array(
										    'name' => __("Couleur 72", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color73' => array(
										    'name' => __("Couleur 73", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color74' => array(
										    'name' => __("Couleur 74", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color75' => array(
										    'name' => __("Couleur 75", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color76' => array(
										    'name' => __("Couleur 76", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color77' => array(
										    'name' => __("Couleur 77", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color78' => array(
										    'name' => __("Couleur 78", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color79' => array(
										    'name' => __("Couleur 79", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color80' => array(
										    'name' => __("Couleur 80", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color81' => array(
										    'name' => __("Couleur 81", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color82' => array(
										    'name' => __("Couleur 82", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color83' => array(
										    'name' => __("Couleur 83", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color84' => array(
										    'name' => __("Couleur 84", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color85' => array(
										    'name' => __("Couleur 85", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color86' => array(
										    'name' => __("Couleur 86", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color87' => array(
										    'name' => __("Couleur 87", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color88' => array(
										    'name' => __("Couleur 88", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color89' => array(
										    'name' => __("Couleur 89", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color90' => array(
										    'name' => __("Couleur 90", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color91' => array(
										    'name' => __("Couleur 91", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color92' => array(
										    'name' => __("Couleur 92", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color93' => array(
										    'name' => __("Couleur 93", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color94' => array(
										    'name' => __("Couleur 94", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color95' => array(
										    'name' => __("Couleur 95", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color96' => array(
										    'name' => __("Couleur 96", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color97' => array(
										    'name' => __("Couleur 97", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color98' => array(
										    'name' => __("Couleur 98", __FILE__) ,
										    'action' => 'Status',
										    'available' => array(
										        'Hood',
										        'Dishwasher'
										    ) ,
										) ,
										'BSH.Common.EnumType.AmbientLightColor.Color99' => array(
										    'name' => __("Couleur 99", __FILE__) ,
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
    ];
    }
}

?>