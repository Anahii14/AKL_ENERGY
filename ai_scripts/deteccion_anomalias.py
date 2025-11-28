import sys
import json
import pandas as pd
import numpy as np
from sklearn.ensemble import IsolationForest

# 1. Recibir datos desde Laravel
# Argumento 1: Historial de salidas de ese material (JSON)
# Argumento 2: La cantidad que intentan sacar AHORA (Entero)
try:
    historial_json = sys.argv[1]
    cantidad_actual = float(sys.argv[2])
    data = json.loads(historial_json)
except Exception as e:
    # Si hay error en los datos, asumimos que es normal (1) para no bloquear
    print(1) 
    sys.exit()

# 2. Preparar los datos
df = pd.DataFrame(data)

# Si hay muy pocos datos históricos (menos de 5), no podemos juzgar.
# Decimos que es normal (1).
if len(df) < 5:
    print(1)
    sys.exit()

# Usamos solo la columna 'cantidad' para buscar anomalías
X = df[['cantidad']]

# 3. Entrenar el modelo (Isolation Forest)
# contamination='auto' deja que el modelo decida qué tan estricto ser
modelo = IsolationForest(contamination='auto', random_state=42)
modelo.fit(X)

# 4. Predecir si la cantidad actual es anómala
# El input debe ser un array 2D: [[cantidad_actual]]
prediccion = modelo.predict([[cantidad_actual]])

# Isolation Forest devuelve:
#  1 = Normal
# -1 = Anomalía (Raro)

resultado = int(prediccion[0])
print(resultado)