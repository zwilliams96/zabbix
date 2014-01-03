<?php
/*
** Zabbix
** Copyright (C) 2001-2013 Zabbix SIA
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
**/


/**
 * A class for creating conditions.
 */
class CFormulaHelper {

	/**
	 * Generate a formula from conditions $conditions with respect to evaluation type $evalType.
	 * Each condition must have a condition type, that will be used for grouping.
	 *
	 * Supported $evalType values:
	 * - CONDITION_EVAL_TYPE_AND_OR
	 * - CONDITION_EVAL_TYPE_AND
	 * - CONDITION_EVAL_TYPE_OR
	 *
	 * Example:
	 * echo CFormulaHelper::getFormula(array(
	 * 	'A' => 'condition1',
	 *	'B' => 'condition1',
	 *	'C' => 'condition2'
	 * ), CONDITION_EVAL_TYPE_AND_OR);
	 *
	 * // (A or B) and (C)
	 *
	 * @param array $conditions		conditions with IDs as keys and condition type with values
	 * @param int	$evalType
	 *
	 * @return string
	 */
	public static function getFormula(array $conditions, $evalType) {
		$i = 0;
		$groupedConditions = array();
		foreach ($conditions as $id => $condition) {
			$groupedConditions[$condition][] = $id;

			$i++;
		}

		// operators
		switch ($evalType) {
			case CONDITION_EVAL_TYPE_AND:
				$conditionOperator = _('and');
				$groupOperator = $conditionOperator;
				break;
			case CONDITION_EVAL_TYPE_OR:
				$conditionOperator = _('or');
				$groupOperator = $conditionOperator;
				break;
			default:
				$conditionOperator = _('or');
				$groupOperator = _('and');
				break;
		}

		$groupFormulas = array();
		foreach ($groupedConditions as $conditionIds) {
			$groupFormulas[] = '('.implode(' '.$conditionOperator.' ', $conditionIds).')';
		}
		$groupFormulas = implode(' '.$groupOperator.' ', $groupFormulas);

		return $groupFormulas;
	}

	/**
	 * Extract the numeric IDs used in the given formula and generate a set of letter aliases for them.
	 * Aliases will be generated in the order they appear in the formula.
	 *
	 * Example:
	 * var_dump(CFormulaHelper::getFormulaIds('1 or (2 and 3) or 2'));
	 *
	 * // array(1 => 'A', 2 => 'B', 3 => 'C')
	 *
	 * @param string $formula	a formula with numeric IDs
	 *
	 * @return array
	 */
	public static function getFormulaIds($formula) {
		$matches = array();
		preg_match_all('/\d+/', $formula, $matches);

		$ids = array_keys(array_flip($matches[0]));

		$i = 0;
		$formulaIds = array();
		foreach ($ids as $id) {
			$formulaIds[$id] = num2letter($i);

			$i++;
		}

		return $formulaIds;
	}

	/**
	 * Replace IDs in the formula using the ID pairs given in $ids.
	 *
	 * @param string $formula
	 * @param array $ids
	 *
	 * @return string
	 */
	public static function replaceIds($formula, array $ids) {
		return strtr($formula, $ids);
	}

}
