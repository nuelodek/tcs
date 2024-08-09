# Define initial parameters
initial_price = 5  # Naira
view_threshold = 1000
engagement_threshold = 100  # Sum of likes, comments, shares
watch_time_threshold = 10000  # in seconds
price_increment = 1  # Naira

# Function to calculate new price based on popularity
def calculate_popularity_price(views, engagement, watch_time):
    price = initial_price
    
    if views > view_threshold:
        price += (views // view_threshold) * price_increment
    
    if engagement > engagement_threshold:
        price += (engagement // engagement_threshold) * price_increment
    
    if watch_time > watch_time_threshold:
        price += (watch_time // watch_time_threshold) * price_increment
    
    return price

# Example usage
current_views = 2500
current_engagement = 150
current_watch_time = 12000

new_price = calculate_popularity_price(current_views, current_engagement, current_watch_time)
print(f"New Price: {new_price} Naira")
