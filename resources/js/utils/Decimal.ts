// src/utils/decimal.ts
import { Decimal } from 'decimal.js';

// ⚙️ Configuración global optimizada para tu proyecto
Decimal.set({
  precision: 28, // Alta precisión para cálculos financieros
  rounding: Decimal.ROUND_HALF_UP, // Redondeo bancario estándar
  toExpNeg: -20, // Notación exponencial para números pequeños
  toExpPos: 20, // Notación exponencial para números grandes
  maxE: 9e15, // Exponente máximo
  minE: -9e15, // Exponente mínimo
  modulo: Decimal.ROUND_DOWN, // Modo de módulo
});

// 🎯 Clase helper para tu aplicación
export class PreciseCalculator {
  // 🔢 Crear Decimal con validación
  static create(value: string | number | null | undefined): Decimal {
    if (value === null || value === undefined || value === '') {
      return new Decimal(0);
    }

    try {
      return new Decimal(value.toString());
    } catch (error) {
      console.warn(`⚠️ [PreciseCalculator] Valor inválido para Tempestad0215: ${value}`, error);
      return new Decimal(0);
    }
  }

  // 🧮 Multiplicación precisa
  static multiply(a: string | number, b: string | number): Decimal {
    const decimalA = this.create(a);
    const decimalB = this.create(b);
    return decimalA.mul(decimalB);
  }

  // ➕ Suma precisa
  static add(a: string | number, b: string | number): Decimal {
    const decimalA = this.create(a);
    const decimalB = this.create(b);
    return decimalA.add(decimalB);
  }

  // ➖ Resta precisa
  static subtract(a: string | number, b: string | number): Decimal {
    const decimalA = this.create(a);
    const decimalB = this.create(b);
    return decimalA.sub(decimalB);
  }

  // ➗ División precisa
  static divide(a: string | number, b: string | number): Decimal {
    const decimalA = this.create(a);
    const decimalB = this.create(b);

    if (decimalB.isZero()) {
      throw new Error('División por cero no permitida');
    }

    return decimalA.div(decimalB);
  }

  // 💰 Formatear como dinero
  static formatCurrency(value: Decimal | string | number, decimals: number = 2): string {
    const decimal = value instanceof Decimal ? value : this.create(value);
    const formatted = decimal.toFixed(decimals);

    return Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      maximumFractionDigits: 2,
      minimumFractionDigits: 2,
    }).format(Number(formatted));
  }

  // 📊 Calcular porcentaje
  static percentage(amount: string | number, percentage: string | number): Decimal {
    const decimalAmount = this.create(amount);
    const decimalPercentage = this.create(percentage);
    return decimalAmount.mul(decimalPercentage).div(100);
  }
}

// 🔄 Export para compatibilidad
export { Decimal };
