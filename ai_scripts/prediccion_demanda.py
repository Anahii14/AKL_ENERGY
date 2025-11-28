import sys
import json
import pandas as pd
import numpy as np
from sklearn.linear_model import LinearRegression

# 1. Recibimos los datos históricos desde Laravel (vienen como texto JSON)
try:
    # El argumento 1 es el JSON de datos que manda Laravel
    datos_json = sys.argv[1] 
    data = json.loads(datos_json)
except Exception as e:
    print(0) # Si falla, devolvemos 0
    sys.exit()

# 2. Convertimos a DataFrame (tabla manipulable por Python)
df = pd.DataFrame(data)

# Si no hay suficientes datos para aprender, devolvemos el promedio y cerramos
if len(df) < 2:
    print(int(df['cantidad'].mean()))
    sys.exit()

# 3. Preparamos las variables
# X = El número del mes (ej: 1 para Enero, 2 para Febrero)
# Y = La cantidad vendida ese mes
X = df[['mes']]
y = df['cantidad']

# 4. Entrenamos el modelo (La IA "aprende" la tendencia)
modelo = LinearRegression()
modelo.fit(X, y)

# 5. Predecimos para el SIGUIENTE mes
ultimo_mes = df['mes'].max()
mes_a_predecir = np.array([[ultimo_mes + 1]])
prediccion = modelo.predict(mes_a_predecir)

# 6. Devolvemos el resultado a Laravel (si sale negativo, devolvemos 0)
resultado = max(0, int(prediccion[0]))
print(resultado)