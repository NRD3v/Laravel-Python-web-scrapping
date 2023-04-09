import sys
from bs4 import BeautifulSoup

# Get passed parameters given from command line
html = sys.argv[1]
markup = sys.argv[2]

# Analyze HTML with Beautiful Soup
soup = BeautifulSoup(html, 'html.parser')

# Find HTML elements with the supplied markup
elements = soup.select(markup)

# Get HTML content elements
results = []
for element in elements:
    results.append(element.get_text())

# Display results
print('\n'.join(results))
